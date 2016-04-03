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
namespace phpOMS\Message\Http;

use phpOMS\Message\HeaderAbstract;
use phpOMS\Utils\ArrayUtils;
use phpOMS\DataStorage\Cookie\CookieJar;
use phpOMS\DataStorage\Session\HttpSession;

/**
 * Response class.
 *
 * @category   Framework
 * @package    phpOMS\Response
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Header extends HeaderAbstract
{

    /**
     * Header.
     *
     * @var string[][]
     * @since 1.0.0
     */
    private $header = [];

    public function __constrct()
    {
        $this->setHeader('Content-Type', 'text/html; charset=utf-8');
    }

    public function getHeaders() : array
    {
        return getallheaders();
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader(string $name) : string
    {
        return getallheaders()[$name];
    }

    /**
     * Remove header by ID.
     *
     * @param int $key Header key
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function remove(int $key) : bool
    {
        if (self::$isLocked) {
            throw new \Exception('Already locked');
        }
        
        if (isset($this->header[$key])) {
            unset($this->header[$key]);

            return true;
        }

        return false;
    }

        /**
     * {@inheritdoc}
     */
    public function get(string $id) : array
    {
        return $this->header[$id] ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $name) : bool
    {
        return array_key_exists($name, $this->header);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, string $header, bool $overwrite = false) : bool
    {
        if (self::$isLocked) {
            throw new \Exception('Already locked');
        }

        if (!$overwrite && isset($this->header[$key])) {
            return false;
        } elseif ($overwrite) {
            unset($this->header[$key]);
        }

        if (!isset($this->header[$key])) {
            $this->header[$key] = [];
        }

        $this->header[$key][] = $header;

        return true;
    }

    /**
     * Push all headers.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function push()
    {
        if (self::$isLocked) {
            throw new \Exception('Already locked');
        }

        foreach ($this->header as $name => $arr) {
            foreach ($arr as $ele => $value) {
                header($name . ': ' . $value);
            }
        }

        $this->lock();
    }

    private function lock() 
    {
        CookieJar::lock();
        HttpSession::lock();
        self::$isLocked = true;
    }

    /**
     * Generate header automatically based on code.
     *
     * @param string $code HTTP status code
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function generate(string $code)
    {
        switch($code) {
            case RequestStatus::R_403:
                $this->generate403();
                break;
            case RequestStatus::R_406:
                $this->generate406();
                break;
            case RequestStatus::R_407:
                $this->generate503();
                break;
            default:
                throw new \Exception('Unexpected header code');
        }
    }

    private function generate403()
    {
        $this->setHeader('HTTP', 'HTTP/1.0 403 Forbidden');
        $this->setHeader('Status', 'Status: HTTP/1.0 403 Forbidden');
    }

    private function generate406() 
    {
        $this->setHeader('HTTP', 'HTTP/1.0 406 Not acceptable');
        $this->setHeader('Status', 'Status: 406 Not acceptable');
    }

    private function generate503()
    {
        $this->setHeader('HTTP', 'HTTP/1.0 503 Service Temporarily Unavailable');
        $this->setHeader('Status', 'Status: 503 Service Temporarily Unavailable');
        $this->setHeader('Retry-After', 'Retry-After: 300');
    }
}