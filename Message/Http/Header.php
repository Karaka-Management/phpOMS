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
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Message\Http;

use phpOMS\Message\HeaderAbstract;

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

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
        $this->set('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, string $header, bool $overwrite = false) : bool
    {
        if (self::$isLocked) {
            throw new \Exception('Already locked');
        }

        $key = strtolower($key);

        if (!$overwrite && isset($this->header[$key])) {
            return false;
        } elseif ($overwrite || !isset($this->header[$key])) {
            if ($this->isSecurityHeader($key) && isset($this->header[$key])) {
                throw new \Exception('Cannot change security headers.');
            }

            unset($this->header[$key]);
        }

        if (!isset($this->header[$key])) {
            $this->header[$key] = [];
        }

        $this->header[$key][] = $header;

        return true;
    }

    /**
     * Is security header.
     *
     * @param string $key Header key
     *
     * @return bool
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function isSecurityHeader(string $key) : bool
    {
        return $key === 'content-security-policy' ||
        $key === 'x-xss-protection' ||
        $key === 'x-content-type-options' ||
        $key === 'x-frame-options';
    }

    /**
     * Get status code.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getStatusCode() : int
    {
        return http_response_code();
    }

    /**
     * Returns all headers.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
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
     * @throws \Exception
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
    public function get(string $key) : array
    {
        return $this->header[strtolower($key)] ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key) : bool
    {
        return array_key_exists($key, $this->header);
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

    /**
     * {@inheritdoc}
     */
    public function generate(string $code)
    {
        switch ($code) {
            case RequestStatus::R_403:
                $this->generate403();
                break;
            case RequestStatus::R_404:
                $this->generate404();
                break;
            case RequestStatus::R_406:
                $this->generate406();
                break;
            case RequestStatus::R_407:
                $this->generate407();
                break;
            case RequestStatus::R_503:
                $this->generate503();
                break;
            default:
                $this->generate500();
        }
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function generate403()
    {
        $this->set('HTTP', 'HTTP/1.0 403 Forbidden');
        $this->set('Status', 'Status: HTTP/1.0 403 Forbidden');
        http_response_code(403);
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function generate404()
    {
        $this->set('HTTP', 'HTTP/1.0 404 Not Found');
        $this->set('Status', 'Status: HTTP/1.0 404 Not Found');
        http_response_code(404);
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function generate406()
    {
        $this->set('HTTP', 'HTTP/1.0 406 Not acceptable');
        $this->set('Status', 'Status: 406 Not acceptable');
        http_response_code(406);
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function generate407()
    {

    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function generate500()
    {
        $this->set('HTTP', 'HTTP/1.0 500 Internal Server Error');
        $this->set('Status', 'Status: 500 Internal Server Error');
        $this->set('Retry-After', 'Retry-After: 300');
        http_response_code(500);
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function generate503()
    {
        $this->set('HTTP', 'HTTP/1.0 503 Service Temporarily Unavailable');
        $this->set('Status', 'Status: 503 Service Temporarily Unavailable');
        $this->set('Retry-After', 'Retry-After: 300');
        http_response_code(503);
    }
}