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

namespace phpOMS\DataStorage\Cookie;

use phpOMS\DataStorage\LockException;

/**
 * CookieJar class
 *
 * @category   Framework
 * @package    phpOMS\Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class CookieJar
{
    /**
     * Locked.
     *
     * @var bool
     * @since 1.0.0
     */
    private static $isLocked = false;
    /**
     * Cookie values.
     *
     * @var array
     * @since 1.0.0
     */
    private $cookies = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }

    /**
     * Lock
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function lock() /* : void */
    {
        self::$isLocked = true;
    }

    /**
     * Is locked?
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function isLocked() : bool
    {
        return self::$isLocked;
    }

    /**
     * Set pending cookie
     *
     * @param string $id        Cookie id
     * @param mixed  $value     Cookie value
     * @param int    $expire    Expire time
     * @param string $path      Path
     * @param string $domain    Domain
     * @param bool   $secure    Is secure
     * @param bool   $httpOnly  Allow only http access
     * @param bool   $overwrite Overwrite if already set
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function set(string $id, $value, int $expire = 86400, string $path = '/', string $domain = null, bool $secure = false, bool $httpOnly = true, bool $overwrite = true) : bool
    {
        if ($overwrite || !isset($this->cookies[$id])) {
            $this->cookies[$id] = [
                'value'    => $value,
                'expiry'   => $expire,
                'path'     => $path,
                'domain'   => $domain,
                'secure'   => $secure,
                'httponly' => $httpOnly,
            ];

            return true;
        }

        return false;
    }

    /**
     * Delete already set cookie
     *
     * @param string $id Cookie id to remove
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function delete(string $id) /* : void */
    {
        $this->remove($id);
        setcookie($id, '', time() - 3600);
    }

    /**
     * Remove pending cookie
     *
     * @param string $id Cookie id to remove
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function remove(string $id) : bool
    {
        if (isset($this->cookies[$id])) {
            unset($this->cookies[$id]);

            return true;
        }

        return false;
    }

    /**
     * Save cookie
     *
     * @return void
     *
     * @throws LockException
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function save() /* : void */
    {
        if (self::$isLocked) {
            throw new LockException('CookieJar');
        }

        foreach ($this->cookies as $key => $cookie) {
            setcookie($key, $cookie['value'], $cookie['expiry'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
        }
    }
}
