<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
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
/*
NOT IN USE
Will be implemented later
*/
/* TODO: implement */
namespace phpOMS\Cookie;

/**
 * @since  1.0.0
 * @author Dennis Eichhorn <d.eichhorn@oms.com>
 */
class CookieJar
{
    private $cookies = [];

    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }

    public function set($id, $value, int $expiry = 86400, $path = '/', $domain = null, bool $secure = false, bool $httponly = true, bool $overwrite = true) : bool
    {
        if ($overwrite || !isset($this->cookies[$id])) {
            $this->cookies[$id] = [
                'value'    => $value,
                'expiry'   => $expiry,
                'path'     => $path,
                'domain'   => $domain,
                'secure'   => $secure,
                'httponly' => $httponly,
            ];

            return true;
        }

        return false;
    }

    public function remove($id) : bool
    {
        if (isset($this->cookies[$id])) {
            unset($this->cookies[$id]);

            return true;
        }

        return false;
    }

    public function delete($id) : bool
    {
        $this->remove($id);
        setcookie($id, '', time() - 3600);
    }

    public function save()
    {
        foreach ($this->cookies as $key => $cookie) {
            setcookie($key, $cookie['value'], $cookie['expiry'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
        }
    }
}
