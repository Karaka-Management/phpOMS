<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Http
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Http;

use phpOMS\Message\HeaderAbstract;
use phpOMS\System\MimeType;

/**
 * Response class.
 *
 * @package phpOMS\Message\Http
 * @license OMS License 2.0
 * @link    https://jingga.app
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
     * Response status.
     *
     * @var int
     * @since 1.0.0
     */
    public int $status = RequestStatusCode::R_200;

    public function initCurrentRequest() : void
    {
        $this->header = self::getAllHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, string $header, bool $overwrite = false) : bool
    {
        if ($this->isLocked) {
            return false;
        }

        $key    = \strtolower($key);
        $exists = isset($this->header[$key]);

        if (!$overwrite && $exists) {
            return false;
        }

        if ($exists && self::isSecurityHeader($key)) {
            return false;
        }

        if ($exists && $overwrite) {
            unset($this->header[$key]);
            $exists = false;
        }

        if (!$exists) {
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
            $part = \substr($name, 0, 5);
            if ($part === 'HTTP_') {
                self::$serverHeaders[
                    \str_replace(
                        ' ',
                        '-',
                        \strtolower(
                            \str_replace('_', ' ', \substr($name, 5))
                        )
                    )
                ] = $value;
            }
        }

        $temp = [];
        foreach (self::$serverHeaders as $key => $value) {
            $key = \strtolower($key);
            if (!isset($temp[$key])) {
                $temp[$key] = [];
            }

            $values = \explode(',', $value);
            foreach ($values as $val) {
                $temp[$key][] = \trim($val);
            }
        }

        self::$serverHeaders = $temp;

        return self::$serverHeaders;
    }

    /**
     * Remove header by ID.
     *
     * @param string $key Header key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function remove(string $key) : bool
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

        $this->generate($this->status);

        foreach ($this->header as $name => $arr) {
            foreach ($arr as $value) {
                \header(empty($name)
                    ? $value
                    : $name . ': ' . $value
                );
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
            case RequestStatusCode::R_100:
                $this->set('', 'HTTP/1.0 100 Continue');
                $this->set('Status', '100 Continue');
                break;
            case RequestStatusCode::R_102:
                $this->set('', 'HTTP/1.0 102 Processing');
                $this->set('Status', '102 Processing');
                break;
            case RequestStatusCode::R_200:
                $this->set('', 'HTTP/1.0 200 OK');
                $this->set('Status', '200 OK');
                break;
            case RequestStatusCode::R_201:
                $this->set('', 'HTTP/1.0 201 Created');
                $this->set('Status', '201 Created');
                break;
            case RequestStatusCode::R_202:
                $this->set('', 'HTTP/1.0 202 Accepted');
                $this->set('Status', '202 Accepted');
                break;
            case RequestStatusCode::R_204:
                $this->set('', 'HTTP/1.0 204 No Content');
                $this->set('Status', '204 No Content');
                break;
            case RequestStatusCode::R_205:
                $this->set('', 'HTTP/1.0 205 Reset Content');
                $this->set('Status', '205 Reset Content');
                break;
            case RequestStatusCode::R_206:
                $this->set('', 'HTTP/1.0 206 Partial Content');
                $this->set('Status', '206 Partial Content');
                break;
            case RequestStatusCode::R_301:
                $this->set('', 'HTTP/1.0 301 Moved Permanently');
                $this->set('Status', '301 Moved Permanently');
                break;
            case RequestStatusCode::R_302:
                $this->set('', 'HTTP/1.0 302 Found');
                $this->set('Status', '302 Found');
                break;
            case RequestStatusCode::R_303:
                $this->set('', 'HTTP/1.0 303 See Other');
                $this->set('Status', '303 See Other');
                break;
            case RequestStatusCode::R_304:
                $this->set('', 'HTTP/1.0 304 Not Modified');
                $this->set('Status', '304 Not Modified');
                break;
            case RequestStatusCode::R_307:
                $this->set('', 'HTTP/1.0 307 Temporary Redirect');
                $this->set('Status', '307 Temporary Redirect');
                break;
            case RequestStatusCode::R_308:
                $this->set('', 'HTTP/1.0 308 Permanent Redirect');
                $this->set('Status', '308 Permanent Redirect');
                break;
            case RequestStatusCode::R_400:
                $this->set('', 'HTTP/1.0 400 Bad Request');
                $this->set('Status', '400 Bad Request');
                break;
            case RequestStatusCode::R_401:
                $this->set('', 'HTTP/1.0 401 Unauthorized');
                $this->set('Status', '401 Unauthorized');
                break;
            case RequestStatusCode::R_402:
                $this->set('', 'HTTP/1.0 402 Payment Required');
                $this->set('Status', '402 Payment Required');
                break;
            case RequestStatusCode::R_403:
                $this->set('', 'HTTP/1.0 403 Forbidden');
                $this->set('Status', '403 Forbidden');
                break;
            case RequestStatusCode::R_404:
                $this->set('', 'HTTP/1.0 404 Not Found');
                $this->set('Status', '404 Not Found');
                break;
            case RequestStatusCode::R_405:
                $this->set('', 'HTTP/1.0 405 Method Not Allowed');
                $this->set('Status', '405 Method Not Allowed');
                break;
            case RequestStatusCode::R_406:
                $this->set('', 'HTTP/1.0 406 Not acceptable');
                $this->set('Status', '406 Not acceptable');
                break;
            case RequestStatusCode::R_407:
                $this->set('', 'HTTP/1.0 407 Proxy Authentication Required');
                $this->set('Status', '407 Proxy Authentication Required');
                break;
            case RequestStatusCode::R_408:
                $this->set('', 'HTTP/1.0 408 Request Timeout');
                $this->set('Status', '408 Request Timeout');
                break;
            case RequestStatusCode::R_409:
                $this->set('', 'HTTP/1.0 409 Conflict');
                $this->set('Status', '409 Conflict');
                break;
            case RequestStatusCode::R_410:
                $this->set('', 'HTTP/1.0 410 Gone');
                $this->set('Status', '410 Gone');
                break;
            case RequestStatusCode::R_411:
                $this->set('', 'HTTP/1.0 411 Length Required');
                $this->set('Status', '411 Length Required');
                break;
            case RequestStatusCode::R_412:
                $this->set('', 'HTTP/1.0 412 Precondition Failed');
                $this->set('Status', '412 Precondition Failed');
                break;
            case RequestStatusCode::R_413:
                $this->set('', 'HTTP/1.0 413 Request Entity Too Large');
                $this->set('Status', '413 Request Entity Too Large');
                break;
            case RequestStatusCode::R_414:
                $this->set('', 'HTTP/1.0 414 Request-URI Too Long');
                $this->set('Status', '414 Request-URI Too Long');
                break;
            case RequestStatusCode::R_415:
                $this->set('', 'HTTP/1.0 415 Unsupported Media Type');
                $this->set('Status', '415 Unsupported Media Type');
                break;
            case RequestStatusCode::R_416:
                $this->set('', 'HTTP/1.0 416 Requested Range Not Satisfiable');
                $this->set('Status', '416 Requested Range Not Satisfiable');
                break;
            case RequestStatusCode::R_417:
                $this->set('', 'HTTP/1.0 417 Expectation Failed');
                $this->set('Status', '417 Expectation Failed');
                break;
            case RequestStatusCode::R_421:
                $this->set('', 'HTTP/1.0 421 Misdirected Request');
                $this->set('Status', '421 Misdirected Request');
                break;
            case RequestStatusCode::R_422:
                $this->set('', 'HTTP/1.0 422 Unprocessable Entity');
                $this->set('Status', '422 Unprocessable Entity');
                break;
            case RequestStatusCode::R_423:
                $this->set('', 'HTTP/1.0 423 Locked');
                $this->set('Status', '423 Locked');
                break;
            case RequestStatusCode::R_424:
                $this->set('', 'HTTP/1.0 424 Failed Dependency');
                $this->set('Status', '424 Failed Dependency');
                break;
            case RequestStatusCode::R_425:
                $this->set('', 'HTTP/1.0 425 Too Early');
                $this->set('Status', '425 Too Early');
                break;
            case RequestStatusCode::R_426:
                $this->set('', 'HTTP/1.0 426 Upgrade Required');
                $this->set('Status', '426 Upgrade Required');
                break;
            case RequestStatusCode::R_428:
                $this->set('', 'HTTP/1.0 428 Precondition Required');
                $this->set('Status', '428 Precondition Required');
                break;
            case RequestStatusCode::R_429:
                $this->set('', 'HTTP/1.0 429 Too Many Requests');
                $this->set('Status', '429 Too Many Requests');
                break;
            case RequestStatusCode::R_431:
                $this->set('', 'HTTP/1.0 431 Request Header Fields Too Large');
                $this->set('Status', '431 Request Header Fields Too Large');
                break;
            case RequestStatusCode::R_451:
                $this->set('', 'HTTP/1.0 451 Unavailable For Legal Reasons');
                $this->set('Status', '451 Unavailable For Legal Reasons');
                break;
            case RequestStatusCode::R_501:
                $this->set('', 'HTTP/1.0 501 Not Implemented');
                $this->set('Status', '501 Not Implemented');
                break;
            case RequestStatusCode::R_502:
                $this->set('', 'HTTP/1.0 502 Bad Gateway');
                $this->set('Status', '502 Bad Gateway');
                break;
            case RequestStatusCode::R_503:
                $this->set('', 'HTTP/1.0 503 Service Temporarily Unavailable');
                $this->set('Status', '503 Service Temporarily Unavailable');
                $this->set('Retry-After', 'Retry-After: 300');
                break;
            case RequestStatusCode::R_504:
                $this->set('', 'HTTP/1.0 504 Gateway Timeout');
                $this->set('Status', '504 Gateway Timeout');
                break;
            case RequestStatusCode::R_507:
                $this->set('', 'HTTP/1.0 507 Insufficient Storage');
                $this->set('Status', '507 Insufficient Storage');
                break;
            case RequestStatusCode::R_508:
                $this->set('', 'HTTP/1.0 508 Loop Detected');
                $this->set('Status', '508 Loop Detected');
                break;
            case RequestStatusCode::R_511:
                $this->set('', 'HTTP/1.0 511 Network Authentication Required');
                $this->set('Status', '511 Network Authentication Required');
                break;
            case RequestStatusCode::R_500:
            default:
                $this->set('', 'HTTP/1.0 500 Internal Server Error');
                $this->set('Status', '500 Internal Server Error');
        }
    }
}
