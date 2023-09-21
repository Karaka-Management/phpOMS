<?php
/**
 * Jingga
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
     * @var array
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

    /**
     * Init header from current request.
     *
     * @return void
     *
     * @since 1.0.0
     */
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
     * Get the referer link
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getReferer() : string
    {
        return $_SERVER['HTTP_REFERER'] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTime() : int
    {
        return (int) ($_SERVER['REQUEST_TIME'] ?? $this->timestamp);
    }

    /**
     * Get the ip of the requester
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getRequestIp() : string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    }

    /**
     * Get the browser/agent name of the request
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getBrowserName() : string
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        if (\strpos($userAgent, 'Opera') !== false || \strpos($userAgent, 'OPR/') !== false) {
            return 'Opera';
        } elseif (\strpos($userAgent, 'Edge') !== false || \strpos($userAgent, 'Edg/') !== false) {
            return 'Microsoft Edge';
        } elseif (\strpos($userAgent, 'Chrome') !== false) {
            return 'Google Chrome';
        } elseif (\strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (\strpos($userAgent, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        } elseif (\strpos($userAgent, 'MSIE') !== false || \strpos($userAgent, 'Trident/7') !== false) {
            return 'Internet Explorer';
        }

        return 'Unknown';
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
                    \strtr(
                        \strtolower(
                            \strtr(\substr($name, 5), '_', ' ')
                        ),
                        ' ',
                        '-'
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
     * @throws \Exception
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
                $this->set('', 'HTTP/1.0 100 Continue', true);
                $this->set('Status', '100 Continue', true);
                break;
            case RequestStatusCode::R_101:
                $this->set('', 'HTTP/1.0 101 Switching protocols', true);
                $this->set('Status', '101 Switching protocols', true);
                break;
            case RequestStatusCode::R_102:
                $this->set('', 'HTTP/1.0 102 Processing', true);
                $this->set('Status', '102 Processing', true);
                break;
            case RequestStatusCode::R_200:
                $this->set('', 'HTTP/1.0 200 OK', true);
                $this->set('Status', '200 OK', true);
                break;
            case RequestStatusCode::R_201:
                $this->set('', 'HTTP/1.0 201 Created', true);
                $this->set('Status', '201 Created', true);
                break;
            case RequestStatusCode::R_202:
                $this->set('', 'HTTP/1.0 202 Accepted', true);
                $this->set('Status', '202 Accepted', true);
                break;
            case RequestStatusCode::R_203:
                $this->set('', 'HTTP/1.0 203 Non-Authoritative Information', true);
                $this->set('Status', '203 Non-Authoritative Information', true);
                break;
            case RequestStatusCode::R_204:
                $this->set('', 'HTTP/1.0 204 No Content', true);
                $this->set('Status', '204 No Content', true);
                break;
            case RequestStatusCode::R_205:
                $this->set('', 'HTTP/1.0 205 Reset Content', true);
                $this->set('Status', '205 Reset Content', true);
                break;
            case RequestStatusCode::R_206:
                $this->set('', 'HTTP/1.0 206 Partial Content', true);
                $this->set('Status', '206 Partial Content', true);
                break;
            case RequestStatusCode::R_207:
                $this->set('', 'HTTP/1.0 207 Multi-Status', true);
                $this->set('Status', '207 Multi-Status', true);
                break;
            case RequestStatusCode::R_300:
                $this->set('', 'HTTP/1.0 300 Multiple Choices', true);
                $this->set('Status', '300 Multiple Choices', true);
                break;
            case RequestStatusCode::R_301:
                $this->set('', 'HTTP/1.0 301 Moved Permanently', true);
                $this->set('Status', '301 Moved Permanently', true);
                break;
            case RequestStatusCode::R_302:
                $this->set('', 'HTTP/1.0 302 Found', true);
                $this->set('Status', '302 Found', true);
                break;
            case RequestStatusCode::R_303:
                $this->set('', 'HTTP/1.0 303 See Other', true);
                $this->set('Status', '303 See Other', true);
                break;
            case RequestStatusCode::R_304:
                $this->set('', 'HTTP/1.0 304 Not Modified', true);
                $this->set('Status', '304 Not Modified', true);
                break;
            case RequestStatusCode::R_305:
                $this->set('', 'HTTP/1.0 305 Use Proxy', true);
                $this->set('Status', '305 Use Proxy', true);
                break;
            case RequestStatusCode::R_306:
                $this->set('', 'HTTP/1.0 306 Switch Proxy', true);
                $this->set('Status', '306 Switch Proxy', true);
                break;
            case RequestStatusCode::R_307:
                $this->set('', 'HTTP/1.0 307 Temporary Redirect', true);
                $this->set('Status', '307 Temporary Redirect', true);
                break;
            case RequestStatusCode::R_308:
                $this->set('', 'HTTP/1.0 308 Permanent Redirect', true);
                $this->set('Status', '308 Permanent Redirect', true);
                break;
            case RequestStatusCode::R_400:
                $this->set('', 'HTTP/1.0 400 Bad Request', true);
                $this->set('Status', '400 Bad Request', true);
                break;
            case RequestStatusCode::R_401:
                $this->set('', 'HTTP/1.0 401 Unauthorized', true);
                $this->set('Status', '401 Unauthorized', true);
                break;
            case RequestStatusCode::R_402:
                $this->set('', 'HTTP/1.0 402 Payment Required', true);
                $this->set('Status', '402 Payment Required', true);
                break;
            case RequestStatusCode::R_403:
                $this->set('', 'HTTP/1.0 403 Forbidden', true);
                $this->set('Status', '403 Forbidden', true);
                break;
            case RequestStatusCode::R_404:
                $this->set('', 'HTTP/1.0 404 Not Found', true);
                $this->set('Status', '404 Not Found', true);
                break;
            case RequestStatusCode::R_405:
                $this->set('', 'HTTP/1.0 405 Method Not Allowed', true);
                $this->set('Status', '405 Method Not Allowed', true);
                break;
            case RequestStatusCode::R_406:
                $this->set('', 'HTTP/1.0 406 Not acceptable', true);
                $this->set('Status', '406 Not acceptable', true);
                break;
            case RequestStatusCode::R_407:
                $this->set('', 'HTTP/1.0 407 Proxy Authentication Required', true);
                $this->set('Status', '407 Proxy Authentication Required', true);
                break;
            case RequestStatusCode::R_408:
                $this->set('', 'HTTP/1.0 408 Request Timeout', true);
                $this->set('Status', '408 Request Timeout', true);
                break;
            case RequestStatusCode::R_409:
                $this->set('', 'HTTP/1.0 409 Conflict', true);
                $this->set('Status', '409 Conflict', true);
                break;
            case RequestStatusCode::R_410:
                $this->set('', 'HTTP/1.0 410 Gone', true);
                $this->set('Status', '410 Gone', true);
                break;
            case RequestStatusCode::R_411:
                $this->set('', 'HTTP/1.0 411 Length Required', true);
                $this->set('Status', '411 Length Required', true);
                break;
            case RequestStatusCode::R_412:
                $this->set('', 'HTTP/1.0 412 Precondition Failed', true);
                $this->set('Status', '412 Precondition Failed', true);
                break;
            case RequestStatusCode::R_413:
                $this->set('', 'HTTP/1.0 413 Request Entity Too Large', true);
                $this->set('Status', '413 Request Entity Too Large', true);
                break;
            case RequestStatusCode::R_414:
                $this->set('', 'HTTP/1.0 414 Request-URI Too Long', true);
                $this->set('Status', '414 Request-URI Too Long', true);
                break;
            case RequestStatusCode::R_415:
                $this->set('', 'HTTP/1.0 415 Unsupported Media Type', true);
                $this->set('Status', '415 Unsupported Media Type', true);
                break;
            case RequestStatusCode::R_416:
                $this->set('', 'HTTP/1.0 416 Requested Range Not Satisfiable', true);
                $this->set('Status', '416 Requested Range Not Satisfiable', true);
                break;
            case RequestStatusCode::R_417:
                $this->set('', 'HTTP/1.0 417 Expectation Failed', true);
                $this->set('Status', '417 Expectation Failed', true);
                break;
            case RequestStatusCode::R_421:
                $this->set('', 'HTTP/1.0 421 Misdirected Request', true);
                $this->set('Status', '421 Misdirected Request', true);
                break;
            case RequestStatusCode::R_422:
                $this->set('', 'HTTP/1.0 422 Unprocessable Entity', true);
                $this->set('Status', '422 Unprocessable Entity', true);
                break;
            case RequestStatusCode::R_423:
                $this->set('', 'HTTP/1.0 423 Locked', true);
                $this->set('Status', '423 Locked', true);
                break;
            case RequestStatusCode::R_424:
                $this->set('', 'HTTP/1.0 424 Failed Dependency', true);
                $this->set('Status', '424 Failed Dependency', true);
                break;
            case RequestStatusCode::R_425:
                $this->set('', 'HTTP/1.0 425 Too Early', true);
                $this->set('Status', '425 Too Early', true);
                break;
            case RequestStatusCode::R_426:
                $this->set('', 'HTTP/1.0 426 Upgrade Required', true);
                $this->set('Status', '426 Upgrade Required', true);
                break;
            case RequestStatusCode::R_428:
                $this->set('', 'HTTP/1.0 428 Precondition Required', true);
                $this->set('Status', '428 Precondition Required', true);
                break;
            case RequestStatusCode::R_429:
                $this->set('', 'HTTP/1.0 429 Too Many Requests', true);
                $this->set('Status', '429 Too Many Requests', true);
                break;
            case RequestStatusCode::R_431:
                $this->set('', 'HTTP/1.0 431 Request Header Fields Too Large', true);
                $this->set('Status', '431 Request Header Fields Too Large', true);
                break;
            case RequestStatusCode::R_451:
                $this->set('', 'HTTP/1.0 451 Unavailable For Legal Reasons', true);
                $this->set('Status', '451 Unavailable For Legal Reasons', true);
                break;
            case RequestStatusCode::R_501:
                $this->set('', 'HTTP/1.0 501 Not Implemented', true);
                $this->set('Status', '501 Not Implemented', true);
                break;
            case RequestStatusCode::R_502:
                $this->set('', 'HTTP/1.0 502 Bad Gateway', true);
                $this->set('Status', '502 Bad Gateway', true);
                break;
            case RequestStatusCode::R_503:
                $this->set('', 'HTTP/1.0 503 Service Temporarily Unavailable', true);
                $this->set('Status', '503 Service Temporarily Unavailable', true);
                $this->set('Retry-After', 'Retry-After: 300', true);
                break;
            case RequestStatusCode::R_504:
                $this->set('', 'HTTP/1.0 504 Gateway Timeout', true);
                $this->set('Status', '504 Gateway Timeout', true);
                break;
            case RequestStatusCode::R_505:
                $this->set('', 'HTTP/1.0 505 HTTP Version Not Supported', true);
                $this->set('Status', '505 HTTP Version Not Supported', true);
                break;
            case RequestStatusCode::R_506:
                $this->set('', 'HTTP/1.0 506 HTTP Variant Also Negotiates', true);
                $this->set('Status', '506 HTTP Variant Also Negotiates', true);
                break;
            case RequestStatusCode::R_507:
                $this->set('', 'HTTP/1.0 507 Insufficient Storage', true);
                $this->set('Status', '507 Insufficient Storage', true);
                break;
            case RequestStatusCode::R_508:
                $this->set('', 'HTTP/1.0 508 Loop Detected', true);
                $this->set('Status', '508 Loop Detected', true);
                break;
            case RequestStatusCode::R_510:
                $this->set('', 'HTTP/1.0 510 Not Extended', true);
                $this->set('Status', '510 Not Extended', true);
                break;
            case RequestStatusCode::R_511:
                $this->set('', 'HTTP/1.0 511 Network Authentication Required', true);
                $this->set('Status', '511 Network Authentication Required', true);
                break;
            case RequestStatusCode::R_500:
            default:
                $this->set('', 'HTTP/1.0 500 Internal Server Error', true);
                $this->set('Status', '500 Internal Server Error', true);
        }
    }
}
