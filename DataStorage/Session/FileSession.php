<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage\Session
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Session;

use phpOMS\DataStorage\LockException;

/**
 * File session class.
 *
 * @package phpOMS\DataStorage\Session
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class FileSession implements SessionInterface
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
    private array $sessionData = [];

    /**
     * Session ID.
     *
     * @var string
     * @since 1.0.0
     */
    private string $sid;

    /**
     * Inactivity Interval.
     *
     * @var int
     * @since 1.0.0
     */
    private int $inactivityInterval = 0;

    /**
     * Constructor.
     *
     * @param int    $liftetime          Session life time
     * @param string $sid                Session id
     * @param int    $inactivityInterval Interval for session activity
     *
     * @throws LockException throws this exception if the session is alrady locked for further interaction
     *
     * @since 1.0.0
     */
    public function __construct(int $liftetime = 3600, string $sid = '', int $inactivityInterval = 0)
    {
        if (\session_id()) {
            \session_write_close(); // @codeCoverageIgnore
        }

        if ($sid !== '') {
            \session_id((string) $sid);
        }

        $this->inactivityInterval = $inactivityInterval;

        if (\session_status() !== \PHP_SESSION_ACTIVE && !\headers_sent()) {
            // @codeCoverageIgnoreStart
            \session_set_cookie_params($liftetime, '/', '', false, true);
            \session_start();
            // @codeCoverageIgnoreEnd
        }

        if ($this->inactivityInterval > 0
            && ($this->inactivityInterval + ($_SESSION['lastActivity'] ?? 0) < \time())
        ) {
            $this->destroy(); // @codeCoverageIgnore
        }

        $this->sessionData                 = $_SESSION ?? [];
        $_SESSION                          = null;
        $this->sessionData['lastActivity'] = \time();
        $this->sid                         = \session_id();
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, mixed $value, bool $overwrite = false) : bool
    {
        if (!$this->isLocked && ($overwrite || !isset($this->sessionData[$key]))) {
            $this->sessionData[$key] = $value;

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key) : mixed
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

        $_SESSION = $this->sessionData;
        \session_write_close();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key) : bool
    {
        if (!$this->isLocked && isset($this->sessionData[$key])) {
            unset($this->sessionData[$key]);

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
            $this->sessionData = [];
            \session_start();
        }
    }

    /**
     * Destruct session.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __destruct()
    {
        $this->save();
    }
}
