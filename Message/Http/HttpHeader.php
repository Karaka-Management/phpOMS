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
                $this->generate100();
                break;
            case RequestStatusCode::R_102:
                $this->generate102();
                break;
            case RequestStatusCode::R_200:
                $this->generate200();
                break;
            case RequestStatusCode::R_201:
                $this->generate201();
                break;
            case RequestStatusCode::R_202:
                $this->generate202();
                break;
            case RequestStatusCode::R_204:
                $this->generate204();
                break;
            case RequestStatusCode::R_205:
                $this->generate205();
                break;
            case RequestStatusCode::R_206:
                $this->generate206();
                break;
            case RequestStatusCode::R_301:
                $this->generate301();
                break;
            case RequestStatusCode::R_302:
                $this->generate302();
                break;
            case RequestStatusCode::R_303:
                $this->generate303();
                break;
            case RequestStatusCode::R_304:
                $this->generate304();
                break;
            case RequestStatusCode::R_307:
                $this->generate307();
                break;
            case RequestStatusCode::R_308:
                $this->generate308();
                break;
            case RequestStatusCode::R_400:
                $this->generate400();
                break;
            case RequestStatusCode::R_401:
                $this->generate401();
                break;
            case RequestStatusCode::R_402:
                $this->generate402();
                break;
            case RequestStatusCode::R_403:
                $this->generate403();
                break;
            case RequestStatusCode::R_404:
                $this->generate404();
                break;
            case RequestStatusCode::R_405:
                $this->generate405();
                break;
            case RequestStatusCode::R_406:
                $this->generate406();
                break;
            case RequestStatusCode::R_407:
                $this->generate407();
                break;
            case RequestStatusCode::R_408:
                $this->generate408();
                break;
            case RequestStatusCode::R_409:
                $this->generate409();
                break;
            case RequestStatusCode::R_410:
                $this->generate410();
                break;
            case RequestStatusCode::R_411:
                $this->generate411();
                break;
            case RequestStatusCode::R_412:
                $this->generate412();
                break;
            case RequestStatusCode::R_413:
                $this->generate413();
                break;
            case RequestStatusCode::R_414:
                $this->generate414();
                break;
            case RequestStatusCode::R_415:
                $this->generate415();
                break;
            case RequestStatusCode::R_416:
                $this->generate416();
                break;
            case RequestStatusCode::R_417:
                $this->generate417();
                break;
            case RequestStatusCode::R_421:
                $this->generate421();
                break;
            case RequestStatusCode::R_422:
                $this->generate422();
                break;
            case RequestStatusCode::R_423:
                $this->generate423();
                break;
            case RequestStatusCode::R_424:
                $this->generate424();
                break;
            case RequestStatusCode::R_426:
                $this->generate426();
                break;
            case RequestStatusCode::R_428:
                $this->generate428();
                break;
            case RequestStatusCode::R_429:
                $this->generate429();
                break;
            case RequestStatusCode::R_431:
                $this->generate431();
                break;
            case RequestStatusCode::R_451:
                $this->generate451();
                break;
            case RequestStatusCode::R_500:
                $this->generate500();
                break;
            case RequestStatusCode::R_501:
                $this->generate501();
                break;
            case RequestStatusCode::R_502:
                $this->generate502();
                break;
            case RequestStatusCode::R_503:
                $this->generate503();
                break;
            case RequestStatusCode::R_504:
                $this->generate504();
                break;
            case RequestStatusCode::R_507:
                $this->generate507();
                break;
            case RequestStatusCode::R_508:
                $this->generate508();
                break;
            case RequestStatusCode::R_511:
                $this->generate511();
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
    private function generate100() : void
    {
        $this->set('', 'HTTP/1.0 100 Continue');
        $this->set('Status', '100 Continue');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate102() : void
    {
        $this->set('', 'HTTP/1.0 102 Processing');
        $this->set('Status', '102 Processing');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate200() : void
    {
        $this->set('', 'HTTP/1.0 200 OK');
        $this->set('Status', '200 OK');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate201() : void
    {
        $this->set('', 'HTTP/1.0 201 Created');
        $this->set('Status', '201 Created');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate202() : void
    {
        $this->set('', 'HTTP/1.0 202 Accepted');
        $this->set('Status', '202 Accepted');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate204() : void
    {
        $this->set('', 'HTTP/1.0 204 No Content');
        $this->set('Status', '204 No Content');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate205() : void
    {
        $this->set('', 'HTTP/1.0 205 Reset Content');
        $this->set('Status', '205 Reset Content');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate206() : void
    {
        $this->set('', 'HTTP/1.0 206 Partial Content');
        $this->set('Status', '206 Partial Content');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate301() : void
    {
        $this->set('', 'HTTP/1.0 301 Moved Permanently');
        $this->set('Status', '301 Moved Permanently');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate302() : void
    {
        $this->set('', 'HTTP/1.0 302 Found');
        $this->set('Status', '302 Found');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate303() : void
    {
        $this->set('', 'HTTP/1.0 303 See Other');
        $this->set('Status', '303 See Other');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate304() : void
    {
        $this->set('', 'HTTP/1.0 304 Not Modified');
        $this->set('Status', '304 Not Modified');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate307() : void
    {
        $this->set('', 'HTTP/1.0 307 Temporary Redirect');
        $this->set('Status', '307 Temporary Redirect');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate308() : void
    {
        $this->set('', 'HTTP/1.0 308 Permanent Redirect');
        $this->set('Status', '308 Permanent Redirect');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate400() : void
    {
        $this->set('', 'HTTP/1.0 400 Bad Request');
        $this->set('Status', '400 Bad Request');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate401() : void
    {
        $this->set('', 'HTTP/1.0 401 Unauthorized');
        $this->set('Status', '401 Unauthorized');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate402() : void
    {
        $this->set('', 'HTTP/1.0 402 Payment Required');
        $this->set('Status', '402 Payment Required');
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
        $this->set('', 'HTTP/1.0 403 Forbidden');
        $this->set('Status', '403 Forbidden');
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
        $this->set('', 'HTTP/1.0 404 Not Found');
        $this->set('Status', '404 Not Found');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate405() : void
    {
        $this->set('', 'HTTP/1.0 405 Method Not Allowed');
        $this->set('Status', '405 Method Not Allowed');
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
        $this->set('', 'HTTP/1.0 406 Not acceptable');
        $this->set('Status', '406 Not acceptable');
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
        $this->set('', 'HTTP/1.0 407 Proxy Authentication Required');
        $this->set('Status', '407 Proxy Authentication Required');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate408() : void
    {
        $this->set('', 'HTTP/1.0 408 Request Timeout');
        $this->set('Status', '408 Request Timeout');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate409() : void
    {
        $this->set('', 'HTTP/1.0 409 Conflict');
        $this->set('Status', '409 Conflict');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate410() : void
    {
        $this->set('', 'HTTP/1.0 410 Gone');
        $this->set('Status', '410 Gone');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate411() : void
    {
        $this->set('', 'HTTP/1.0 411 Length Required');
        $this->set('Status', '411 Length Required');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate412() : void
    {
        $this->set('', 'HTTP/1.0 412 Precondition Failed');
        $this->set('Status', '412 Precondition Failed');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate413() : void
    {
        $this->set('', 'HTTP/1.0 413 Request Entity Too Large');
        $this->set('Status', '413 Request Entity Too Large');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate414() : void
    {
        $this->set('', 'HTTP/1.0 414 Request-URI Too Long');
        $this->set('Status', '414 Request-URI Too Long');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate415() : void
    {
        $this->set('', 'HTTP/1.0 415 Unsupported Media Type');
        $this->set('Status', '415 Unsupported Media Type');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate416() : void
    {
        $this->set('', 'HTTP/1.0 416 Requested Range Not Satisfiable');
        $this->set('Status', '416 Requested Range Not Satisfiable');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate417() : void
    {
        $this->set('', 'HTTP/1.0 417 Expectation Failed');
        $this->set('Status', '417 Expectation Failed');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate421() : void
    {
        $this->set('', 'HTTP/1.0 421 Misdirected Request');
        $this->set('Status', '421 Misdirected Request');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate422() : void
    {
        $this->set('', 'HTTP/1.0 422 Unprocessable Entity');
        $this->set('Status', '422 Unprocessable Entity');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate423() : void
    {
        $this->set('', 'HTTP/1.0 423 Locked');
        $this->set('Status', '423 Locked');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate424() : void
    {
        $this->set('', 'HTTP/1.0 424 Failed Dependency');
        $this->set('Status', '424 Failed Dependency');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate425() : void
    {
        $this->set('', 'HTTP/1.0 425 Too Early');
        $this->set('Status', '425 Too Early');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate426() : void
    {
        $this->set('', 'HTTP/1.0 426 Upgrade Required');
        $this->set('Status', '426 Upgrade Required');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate428() : void
    {
        $this->set('', 'HTTP/1.0 428 Precondition Required');
        $this->set('Status', '428 Precondition Required');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate429() : void
    {
        $this->set('', 'HTTP/1.0 429 Too Many Requests');
        $this->set('Status', '429 Too Many Requests');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate431() : void
    {
        $this->set('', 'HTTP/1.0 431 Request Header Fields Too Large');
        $this->set('Status', '431 Request Header Fields Too Large');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate451() : void
    {
        $this->set('', 'HTTP/1.0 451 Unavailable For Legal Reasons');
        $this->set('Status', '451 Unavailable For Legal Reasons');
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
        $this->set('', 'HTTP/1.0 500 Internal Server Error');
        $this->set('Status', '500 Internal Server Error');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate501() : void
    {
        $this->set('', 'HTTP/1.0 501 Not Implemented');
        $this->set('Status', '501 Not Implemented');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate502() : void
    {
        $this->set('', 'HTTP/1.0 502 Bad Gateway');
        $this->set('Status', '502 Bad Gateway');
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
        $this->set('', 'HTTP/1.0 503 Service Temporarily Unavailable');
        $this->set('Status', '503 Service Temporarily Unavailable');
        $this->set('Retry-After', 'Retry-After: 300');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate504() : void
    {
        $this->set('', 'HTTP/1.0 504 Gateway Timeout');
        $this->set('Status', '504 Gateway Timeout');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate507() : void
    {
        $this->set('', 'HTTP/1.0 507 Insufficient Storage');
        $this->set('Status', '507 Insufficient Storage');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate508() : void
    {
        $this->set('', 'HTTP/1.0 508 Loop Detected');
        $this->set('Status', '508 Loop Detected');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate511() : void
    {
        $this->set('', 'HTTP/1.0 511 Network Authentication Required');
        $this->set('Status', '511 Network Authentication Required');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate598() : void
    {
        $this->set('', 'HTTP/1.0 598 Network read timeout error');
        $this->set('Status', '598 Network read timeout error');
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generate599() : void
    {
        $this->set('', 'HTTP/1.0 599 Network connect timeout error');
        $this->set('Status', '599 Network connect timeout error');
    }
}
