<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Cookie
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cookie;

use phpOMS\DataStorage\LockException;

/**
 * CookieJar class
 *
 * @package phpOMS\DataStorage\Cookie
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class CookieJar
{
    /**
     * Locked.
     *
     * @var bool
     * @since 1.0.0
     */
    private static bool $isLocked = false;

    /**
     * Cookie values.
     *
     * @var array
     * @since 1.0.0
     */
    private array $cookies = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }

    /**
     * Lock
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function lock() : void
    {
        self::$isLocked = true;
    }

    /**
     * Is locked?
     *
     * @return bool
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function set(
        string $id,
        mixed $value,
        int $expire = 86400,
        string $path = '/',
        ?string $domain = null,
        bool $secure = false,
        bool $httpOnly = true,
        bool $overwrite = true
    ) : bool
    {
        if ($overwrite || !isset($this->cookies[$id])) {
            $this->cookies[$id] = [
                'value'    => $value,
                'expires'  => $expire,
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
     * Get cookie value
     *
     * @param string $id Cookie id
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function get(string $id) : mixed
    {
        return $this->cookies[$id] ?? null;
    }

    /**
     * Delete already set cookie
     *
     * @param string $id Cookie id to remove
     *
     * @return bool
     *
     * @throws LockException Throws this exception if the cookie is already sent
     *
     * @since 1.0.0
     */
    public function delete(string $id) : bool
    {
        if ($this->remove($id)) {
            if (self::$isLocked) {
                throw new LockException('CookieJar');
            }

            // @codeCoverageIgnoreStart
            if (!\headers_sent()) {
                \setcookie($id, '', \time() - 3600);

                return true;
            }

            return false;
            // @codeCoverageIgnoreEnd
        }

        return false;
    }

    /**
     * Remove pending cookie
     *
     * @param string $id Cookie id to remove
     *
     * @return bool
     *
     * @since 1.0.0
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
     * @throws LockException Throws this exception if the cookie is already sent
     *
     * @since 1.0.0
     */
    public function save() : void
    {
        if (self::$isLocked) {
            throw new LockException('CookieJar');
        }

        // @codeCoverageIgnoreStart
        foreach ($this->cookies as $key => $cookie) {
            \setcookie($key, $cookie['value'], [
                'expires'  => $cookie['expires'],
                'path'     => $cookie['path'],
                'domain'   => $cookie['domain'],
                'secure'   => $cookie['secure'],
                'httponly' => $cookie['httponly'],
                'samesite' => 'Strict',
            ]);
        }
        // @codeCoverageIgnoreEnd
    }
}
