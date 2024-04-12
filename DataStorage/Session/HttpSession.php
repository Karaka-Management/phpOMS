<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Session
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Session;

use phpOMS\Message\RequestAbstract;
use phpOMS\Session\JWT;
use phpOMS\Uri\UriFactory;

/**
 * Http session class.
 *
 * @package phpOMS\DataStorage\Session
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class HttpSession implements SessionInterface
{
    /**
     * Is session locked/already set.
     *
     * @var bool
     * @since 1.0.0
     */
    private bool $isLocked = false;

    /**
     * Raw session data.
     *
     * @var array<string, mixed>
     * @since 1.0.0
     */
    public array $data = [];

    /**
     * Session ID.
     *
     * @var string
     * @since 1.0.0
     */
    public string $sid;

    /**
     * Inactivity Interval.
     *
     * @var int
     * @since 1.0.0
     */
    public int $inactivityInterval = 0;

    /**
     * Constructor.
     *
     * @param int    $lifetime           Session life time
     * @param string $sid                Session id
     * @param int    $inactivityInterval Interval for session activity
     *
     * @since 1.0.0
     */
    public function __construct(int $lifetime = 3600, string $sid = '', int $inactivityInterval = 0)
    {
        if (\session_id()) {
            \session_write_close(); // @codeCoverageIgnore
        }

        if ($sid !== '') {
            \session_id((string) $sid); // @codeCoverageIgnore
        }

        $this->inactivityInterval = $inactivityInterval;

        if (\session_status() !== \PHP_SESSION_ACTIVE && !\headers_sent()) {
            // @codeCoverageIgnoreStart
            // samesite: Strict results in losing sessions in some situations when working with iframe
            // This can happen when the iframe content uses relative links
            //      -> loads iframe page
            //      -> iframe page references relative resources (css, js, ...)
            //      -> client browser tries to load resources
            //      -> client browser loads current app based on relative link but without session cookie
            //      -> creates new session cookie for page (not authenticated yet)
            //      -> loses authentication on iframe parent
            // samesite: None would solve that but is way too dangerous.
            \session_set_cookie_params([
                'lifetime' => $lifetime,
                'path'     => '/',
                'domain'   => '',
                'secure'   => false,
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
            \session_start();
            // @codeCoverageIgnoreEnd
        } else {
            \phpOMS\Log\FileLogger::getInstance()->warning(
                \phpOMS\Log\FileLogger::MSG_FULL, [
                    'message' => 'Headers already sent.',
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );
        }

        if ($this->inactivityInterval > 0
            && ($this->inactivityInterval + ($_SESSION['lastActivity'] ?? 0) < \time())
        ) {
            $this->destroy(); // @codeCoverageIgnore
        }

        $this->data                 = $_SESSION ?? [];
        $_SESSION                   = null;
        $this->data['lastActivity'] = \time();
        $this->sid                  = (string) \session_id();

        $this->setCsrfProtection();
    }

    /**
     * Set Csrf protection for forms.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function setCsrfProtection() : void
    {
        $this->set('UID', 0, false);

        if (($csrf = $this->get('CSRF')) === null) {
            $csrf = \bin2hex(\random_bytes(32));
            $this->set('CSRF', $csrf, false);
        }

        UriFactory::setQuery('$CSRF', $csrf); /* @phpstan-ignore-line */
    }

    /**
     * Populate the session from the request.
     *
     * This is only used when the session data is stored in the request itself (e.g. JWT)
     *
     * @param string          $secret  Secret to validate the request
     * @param RequestAbstract $request Request
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function populateFromRequest(string $secret, RequestAbstract $request) : void
    {
        $authentication = $request->header->get('Authorization');
        if (\count($authentication) !== 1) {
            return;
        }

        $explode = \explode(' ', $authentication[0]);
        if (\count($explode) !== 2) {
            return;
        }

        $token  = \trim($explode[1]);
        $header = JWT::getHeader($token);

        if (($header['typ'] ?? '') !== 'jwt' || !JWT::validateJWT($secret, $token)) {
            return;
        }

        $payload = JWT::getPayload($token);
        $this->set('UID', (int) ($payload['uid'] ?? 0));
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, mixed $value, bool $overwrite = false) : bool
    {
        if (!$this->isLocked && ($overwrite || !isset($this->data[$key]))) {
            $this->data[$key] = $value;

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key) : mixed
    {
        return $this->data[$key] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function lock() : void
    {
        $this->isLocked = true;
    }

    /**
     * Check if session is locked.
     *
     * @return bool Lock status
     *
     * @since 1.0.0
     */
    public function isLocked() : bool
    {
        return $this->isLocked;
    }

    /**
     * {@inheritdoc}
     */
    public function save() : bool
    {
        if ($this->isLocked) {
            return false;
        }

        $_SESSION = $this->data;

        return \session_write_close();
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key) : bool
    {
        if (!$this->isLocked && isset($this->data[$key])) {
            unset($this->data[$key]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getSID() : string
    {
        return $this->sid;
    }

    /**
     * {@inheritdoc}
     */
    public function setSID(string $sid) : void
    {
        $this->sid = $sid;
    }

    /**
     * Destroy the current session.
     *
     * @return void
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function destroy() : void
    {
        if (\session_status() !== \PHP_SESSION_NONE) {
            \session_destroy();
            $this->data = [];
            \session_start();
        }
    }

    /**
     * Destruct session.
     *
     * @since 1.0.0
     */
    public function __destruct()
    {
        $this->save();
    }
}
