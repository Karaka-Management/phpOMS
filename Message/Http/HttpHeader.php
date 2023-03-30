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

    /**
     * {@inheritdoc}
     */
    public function set(string $key, string $header, bool $overwrite = false) : bool
    {
        if ($this->isLocked) {
            return false;
        }

        $key = \strtolower($key);
        if (self::isSecurityHeader($key) && isset($this->header[$key])) {
            return false;
        }

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
                        \ucwords(
                            \strtolower(
                                \str_replace('_', ' ', \substr($name, 5))
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
        $this->set('Status', 'Status: HTTP/1.0 100 Continue');
        \http_response_code(100);
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
        $this->set('Status', 'Status: HTTP/1.0 102 Processing');
        \http_response_code(102);
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
        $this->set('Status', 'Status: HTTP/1.0 200 OK');
        \http_response_code(200);
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
        $this->set('Status', 'Status: HTTP/1.0 201 Created');
        \http_response_code(201);
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
        $this->set('Status', 'Status: HTTP/1.0 202 Accepted');
        \http_response_code(202);
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
        $this->set('Status', 'Status: HTTP/1.0 204 No Content');
        \http_response_code(204);
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
        $this->set('Status', 'Status: HTTP/1.0 205 Reset Content');
        \http_response_code(205);
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
        $this->set('Status', 'Status: HTTP/1.0 206 Partial Content');
        \http_response_code(206);
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
        $this->set('Status', 'Status: HTTP/1.0 301 Moved Permanently');
        \http_response_code(301);
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
        $this->set('Status', 'Status: HTTP/1.0 302 Found');
        \http_response_code(302);
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
        $this->set('Status', 'Status: HTTP/1.0 303 See Other');
        \http_response_code(303);
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
        $this->set('Status', 'Status: HTTP/1.0 304 Not Modified');
        \http_response_code(304);
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
        $this->set('Status', 'Status: HTTP/1.0 307 Temporary Redirect');
        \http_response_code(307);
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
        $this->set('Status', 'Status: HTTP/1.0 308 Permanent Redirect');
        \http_response_code(308);
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
        $this->set('Status', 'Status: HTTP/1.0 400 Bad Request');
        \http_response_code(400);
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
        $this->set('Status', 'Status: HTTP/1.0 401 Unauthorized');
        \http_response_code(401);
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
        $this->set('Status', 'Status: HTTP/1.0 402 Payment Required');
        \http_response_code(402);
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
        $this->set('', 'HTTP/1.0 404 Not Found');
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
    private function generate405() : void
    {
        $this->set('', 'HTTP/1.0 405 Method Not Allowed');
        $this->set('Status', 'Status: HTTP/1.0 405 Method Not Allowed');
        \http_response_code(405);
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
        $this->set('', 'HTTP/1.0 407 Proxy Authentication Required');
        $this->set('Status', 'Status: HTTP/1.0 407 Proxy Authentication Required');
        \http_response_code(407);
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
        $this->set('Status', 'Status: HTTP/1.0 408 Request Timeout');
        \http_response_code(408);
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
        $this->set('Status', 'Status: HTTP/1.0 409 Conflict');
        \http_response_code(409);
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
        $this->set('Status', 'Status: HTTP/1.0 410 Gone');
        \http_response_code(410);
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
        $this->set('Status', 'Status: HTTP/1.0 411 Length Required');
        \http_response_code(411);
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
        $this->set('Status', 'Status: HTTP/1.0 412 Precondition Failed');
        \http_response_code(412);
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
        $this->set('Status', 'Status: HTTP/1.0 413 Request Entity Too Large');
        \http_response_code(413);
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
        $this->set('Status', 'Status: HTTP/1.0 414 Request-URI Too Long');
        \http_response_code(414);
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
        $this->set('Status', 'Status: HTTP/1.0 415 Unsupported Media Type');
        \http_response_code(415);
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
        $this->set('Status', 'Status: HTTP/1.0 416 Requested Range Not Satisfiable');
        \http_response_code(416);
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
        $this->set('Status', 'Status: HTTP/1.0 417 Expectation Failed');
        \http_response_code(417);
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
        $this->set('Status', 'Status: HTTP/1.0 421 Misdirected Request');
        \http_response_code(421);
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
        $this->set('Status', 'Status: HTTP/1.0 422 Unprocessable Entity');
        \http_response_code(422);
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
        $this->set('Status', 'Status: HTTP/1.0 423 Locked');
        \http_response_code(423);
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
        $this->set('Status', 'Status: HTTP/1.0 424 Failed Dependency');
        \http_response_code(424);
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
        $this->set('Status', 'Status: HTTP/1.0 425 Too Early');
        \http_response_code(425);
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
        $this->set('Status', 'Status: HTTP/1.0 426 Upgrade Required');
        \http_response_code(426);
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
        $this->set('Status', 'Status: HTTP/1.0 428 Precondition Required');
        \http_response_code(428);
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
        $this->set('Status', 'Status: HTTP/1.0 429 Too Many Requests');
        \http_response_code(429);
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
        $this->set('Status', 'Status: HTTP/1.0 431 Request Header Fields Too Large');
        \http_response_code(431);
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
        $this->set('Status', 'Status: HTTP/1.0 451 Unavailable For Legal Reasons');
        \http_response_code(451);
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
        $this->set('Status', 'Status: HTTP/1.0 500 Internal Server Error');
        \http_response_code(500);
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
        $this->set('Status', 'Status: HTTP/1.0 501 Not Implemented');
        \http_response_code(501);
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
        $this->set('Status', 'Status: HTTP/1.0 502 Bad Gateway');
        \http_response_code(502);
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
    private function generate504() : void
    {
        $this->set('', 'HTTP/1.0 504 Gateway Timeout');
        $this->set('Status', 'Status: HTTP/1.0 504 Gateway Timeout');
        \http_response_code(504);
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
        $this->set('Status', 'Status: HTTP/1.0 507 Insufficient Storage');
        \http_response_code(507);
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
        $this->set('Status', 'Status: HTTP/1.0 508 Loop Detected');
        \http_response_code(508);
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
        $this->set('Status', 'Status: HTTP/1.0 511 Network Authentication Required');
        \http_response_code(511);
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
        $this->set('Status', 'Status: HTTP/1.0 598 Network read timeout error');
        \http_response_code(598);
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
        $this->set('Status', 'Status: HTTP/1.0 599 Network connect timeout error');
        \http_response_code(599);
    }

}
