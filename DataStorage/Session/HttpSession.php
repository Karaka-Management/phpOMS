<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\DataStorage\Session;

use phpOMS\Uri\UriFactory;
use phpOMS\Utils\RnG\StringUtils;
use phpOMS\DataStorage\LockException;

/**
 * Http session class.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Session
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class HttpSession implements SessionInterface
{

    /**
     * Is session locked/already set.
     *
     * @var bool
     * @since 1.0.0
     */
    private static $isLocked = false;

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
     * @var string|int
     * @since 1.0.0
     */
    private $sid = null;

    /**
     * Constructor.
     *
     * @param int             $liftetime Session life time
     * @param string|int|bool $sid       Session id
     *
     * @throws LockException Throws this exception if the session is alrady locked for further interaction.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(int $liftetime = 3600, $sid = false)
    {
        if (self::$isLocked) {
            throw new LockException('HttpSession');
        }

        if (!is_bool($sid)) {
            session_id($sid);
        }

        session_set_cookie_params($liftetime, '/', null, false, true);
        session_start();
        $this->sessionData = $_SESSION;
        $_SESSION          = null;

        $this->sid = session_id();
        $this->setCsrfProtection();
    }

    /**
     * Set Csrf protection for forms.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function setCsrfProtection() /* : void */
    {
        $this->set('UID', 0, false);

        if (($CSRF = $this->get('CSRF')) === null) {
            $CSRF = StringUtils::generateString(10, 16);
            $this->set('CSRF', $CSRF, false);
        }

        UriFactory::setQuery('$CSRF', $CSRF);
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
     * Lock session from further adjustments.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function lock()
    {
        self::$isLocked = true;
    }

    /**
     * Check if session is locked.
     *
     * @return bool Lock status
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function isLocked() : bool
    {
        return self::$isLocked;
    }

    /**
     * {@inheritdoc}
     */
    public function save() /* : void */
    {
        if(!self::$isLocked) {
            $_SESSION = $this->sessionData;
            session_write_close();
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
    public function setSID($sid) /* : void */
    {
        $this->sid = $sid;
    }

    /**
     * Destruct session.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __destruct()
    {
        $this->save();
    }

}
