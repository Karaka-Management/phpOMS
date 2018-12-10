<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Session
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Session;

use phpOMS\DataStorage\LockException;
use phpOMS\Uri\UriFactory;
use phpOMS\Utils\RnG\StringUtils;

/**
 * Http session class.
 *
 * @package    phpOMS\DataStorage\Session
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class HttpSession implements SessionInterface
{

    /**
     * Is session locked/already set.
     *
     * @var bool
     * @since 1.0.0
     */
    private $isLocked = false;

    /**
     * Raw session data.
     *
     * @var array
     * @since 1.0.0
     */
    private $sessionData = [];

    /**
     * Session ID.
     *
     * @var null|int|string
     * @since 1.0.0
     */
    private $sid = null;

    /**
     * Inactivity Interval.
     *
     * @var int
     * @since 1.0.0
     */
    private $inactivityInterval = 0;

    /**
     * Constructor.
     *
     * @param int             $liftetime          Session life time
     * @param bool|int|string $sid                Session id
     * @param int             $inactivityInterval Interval for session activity
     *
     * @throws LockException Throws this exception if the session is alrady locked for further interaction.
     *
     * @since  1.0.0
     */
    public function __construct(int $liftetime = 3600, $sid = false, int $inactivityInterval = 0)
    {
        if ($this->isLocked) {
            throw new LockException('HttpSession');
        }

        if (\session_id()) {
            \session_write_close();
        }

        if (!\is_bool($sid)) {
            \session_id((string) $sid);
        }

        $this->inactivityInterval = $inactivityInterval;

        if (\session_status() !== PHP_SESSION_ACTIVE && !\headers_sent()) {
            \session_set_cookie_params($liftetime, '/', '', false, true);
            \session_start();
        }

        if ($this->inactivityInterval > 0 && ($this->inactivityInterval + ($_SESSION['lastActivity'] ?? 0) < \time())) {
            $this->destroy();
        }

        $this->sessionData                 = $_SESSION;
        $_SESSION                          = null;
        $this->sessionData['lastActivity'] = \time();
        $this->sid                         = \session_id();

        $this->setCsrfProtection();
    }

    /**
     * Set Csrf protection for forms.
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function setCsrfProtection() : void
    {
        $this->set('UID', 0, false);

        if (($csrf = $this->get('CSRF')) === null) {
            $csrf = StringUtils::generateString(10, 16);
            $this->set('CSRF', $csrf, false);
        }

        UriFactory::setQuery('$CSRF', $csrf);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, bool $overwrite = true) : bool
    {
        if ($overwrite || !isset($this->sessionData[$key])) {
            $this->sessionData[$key] = $value;

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return $this->sessionData[$key] ?? null;
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
     * @since  1.0.0
     */
    public function isLocked() : bool
    {
        return $this->isLocked;
    }

    /**
     * {@inheritdoc}
     */
    public function save() : void
    {
        if (!$this->isLocked) {
            $_SESSION = $this->sessionData;
            \session_write_close();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key) : bool
    {
        if (isset($this->sessionData[$key])) {
            unset($this->sessionData[$key]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getSID()
    {
        return $this->sid;
    }

    /**
     * {@inheritdoc}
     */
    public function setSID($sid) : void
    {
        $this->sid = $sid;
    }

    /**
     * Destroy the current session.
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function destroy() : void
    {
        \session_destroy();
        $this->sessionData = [];
        \session_start();
    }

    /**
     * Destruct session.
     *
     * @since  1.0.0
     */
    public function __destruct()
    {
        $this->save();
    }
}
