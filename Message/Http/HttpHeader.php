<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Message\Http
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Http;

use phpOMS\Message\HeaderAbstract;
use phpOMS\System\MimeType;

/**
 * Response class.
 *
 * @package phpOMS\Message\Http
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class HttpHeader extends HeaderAbstract
{
    /**
     * Header.
     *
     * @var string[][]
     * @since 1.0.0
     */
    private array $header = [];

    /**
     * Server headers.
     *
     * @var string[]
     * @since 1.0.0
     */
    private static $serverHeaders = [];

    /**
     * {@inheritdoc}
     */
    public function set(string $key, string $header, bool $overwrite = false) : bool
    {
        if ($this->isLocked) {
            return false;
        }

        if (self::isSecurityHeader($key) && isset($this->header[$key])) {
            return false;
        }

        $key = \strtolower($key);

        if (!$overwrite && isset($this->header[$key])) {
            return false;
        }

        unset($this->header[$key]);

        if (!isset($this->header[$key])) {
            $this->header[$key] = [];
        }

        $this->header[$key][] = $header;

        return true;
    }

    /**
     * Set header as downloadable
     *
     * @param string $name Download name
     * @param string $type Download file type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDownloadable(string $name, string $type) : void
    {
        $this->set('Content-Type', MimeType::M_BIN, true);
        $this->set('Content-Transfer-Encoding', 'Binary', true);
        $this->set(
            'Content-disposition', 'attachment; filename="' . $name . '.' . $type . '"'
        , true);
    }

    /**
     * Is security header.
     *
     * @param string $key Header key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isSecurityHeader(string $key) : bool
    {
        $key = \strtolower($key);

        return $key === 'content-security-policy'
            || $key === 'x-xss-protection'
            || $key === 'x-content-type-options'
            || $key === 'x-frame-options';
    }

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion() : string
    {
        return $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
    }

    /**
     * Get status code.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getStatusCode() : int
    {
        if ($this->status === 0) {
            $this->status = RequestStatusCode::R_200;
        }

        return parent::getStatusCode();
    }

    /**
     * Get all headers for apache and nginx
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getAllHeaders() : array
    {
        if (!empty(self::$serverHeaders)) {
            return self::$serverHeaders;
        }

        if (\function_exists('getallheaders')) {
            // @codeCoverageIgnoreStart
            self::$serverHeaders = \getallheaders();
            // @codeCoverageIgnoreEnd
        }

        foreach ($_SERVER as $name => $value) {
            $part = \substr($name, 5);
            if ($part === 'HTTP_') {
                self::$serverHeaders[
                    \str_replace(
                        ' ',
                        '-',
                        \ucwords(
                            \strtolower(
                                \str_replace('_', ' ', $part)
                            )
                        )
                    )
                ] = $value;
            }
        }

        return self::$serverHeaders;
    }

    /**
     * Remove header by ID.
     *
     * @param mixed $key Header key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function remove($key) : bool
    {
        if ($this->isLocked) {
            return false;
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
    public function getReasonPhrase() : string
    {
        $phrases = $this->get('Status');

        return empty($phrases) ? '' : $phrases[0];
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key = null) : array
    {
        return $key === null ? $this->header : ($this->header[\strtolower($key)] ?? []);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key) : bool
    {
        return isset($this->header[$key]);
    }

    /**
     * Push all headers.
     *
     * @return void
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function push() : void
    {
        if ($this->isLocked) {
            throw new \Exception('Already locked');
        }

        foreach ($this->header as $name => $arr) {
            foreach ($arr as $ele => $value) {
                \header($name . ': ' . $value);
            }
        }

        \header("X-Powered-By: hidden");

        $this->lock();
    }

    /**
     * {@inheritdoc}
     */
    public function generate(int $code) : void
    {
        switch ($code) {
            case RequestStatusCode::R_403:
                $this->generate403();
                break;
            case RequestStatusCode::R_404:
                $this->generate404();
                break;
            case RequestStatusCode::R_406:
                $this->generate406();
                break;
            case RequestStatusCode::R_407:
                $this->generate407();
                break;
            case RequestStatusCode::R_503:
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
     * @since 1.0.0
     */
    private function generate403() : void
    {
        $this->set('HTTP', 'HTTP/1.0 403 Forbidden');
        $this->set('Status', 'Status: HTTP/1.0 403 Forbidden');
        \http_response_code(403);
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate404() : void
    {
        $this->set('HTTP', 'HTTP/1.0 404 Not Found');
        $this->set('Status', 'Status: HTTP/1.0 404 Not Found');
        \http_response_code(404);
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate406() : void
    {
        $this->set('HTTP', 'HTTP/1.0 406 Not acceptable');
        $this->set('Status', 'Status: 406 Not acceptable');
        \http_response_code(406);
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate407() : void
    {
        \http_response_code(407);
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate503() : void
    {
        $this->set('HTTP', 'HTTP/1.0 503 Service Temporarily Unavailable');
        $this->set('Status', 'Status: 503 Service Temporarily Unavailable');
        $this->set('Retry-After', 'Retry-After: 300');
        \http_response_code(503);
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate500() : void
    {
        $this->set('HTTP', 'HTTP/1.0 500 Internal Server Error');
        $this->set('Status', 'Status: 500 Internal Server Error');
        $this->set('Retry-After', 'Retry-After: 300');
        \http_response_code(500);
    }
}
