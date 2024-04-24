<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Message\Http;

include_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Localization;
use phpOMS\Message\Http\HttpHeader;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\System\MimeType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Message\Http\HttpHeader::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Message\Http\HttpHeaderTest: Header for http requests/responses')]
final class HttpHeaderTest extends \PHPUnit\Framework\TestCase
{
    protected HttpHeader $header;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->header = new HttpHeader();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The header has the expected default values after initialization')]
    public function testDefaults() : void
    {
        self::assertFalse($this->header->isLocked());
        self::assertEquals(RequestStatusCode::R_200, $this->header->status);
        self::assertEquals('HTTP/1.1', $this->header->getProtocolVersion());
        self::assertEmpty(HttpHeader::getAllHeaders());
        self::assertEquals('', $this->header->getReasonPhrase());
        self::assertEquals([], $this->header->get('key'));
        self::assertEquals([], $this->header->get());
        self::assertFalse($this->header->has('key'));
        self::assertInstanceOf(Localization::class, $this->header->l11n);
        self::assertEquals(0, $this->header->account);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Security policy headers get correctly identified')]
    public function testSecurityHeader() : void
    {
        self::assertTrue(HttpHeader::isSecurityHeader('content-security-policy'));
        self::assertTrue(HttpHeader::isSecurityHeader('X-xss-protection'));
        self::assertTrue(HttpHeader::isSecurityHeader('x-conTent-tYpe-options'));
        self::assertTrue(HttpHeader::isSecurityHeader('x-frame-options'));
        self::assertFalse(HttpHeader::isSecurityHeader('x-frame-optionss'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Header data can be set, checked for existence and returned')]
    public function testDataInputOutput() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertEquals(['header'], $this->header->get('key'));
        self::assertTrue($this->header->has('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Header data can be forced to get overwritten')]
    public function testOverwrite() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertTrue($this->header->set('key', 'header3', true));
        self::assertEquals(['header3'], $this->header->get('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("By default header data doesn't get overwritten")]
    public function testInvalidOverwrite() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertTrue($this->header->set('key', 'header2'));
        self::assertEquals(['header', 'header2'], $this->header->get('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Header data can be removed')]
    public function testRemove() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertTrue($this->header->remove('key'));
        self::assertFalse($this->header->has('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('None-existing header data cannot be removed')]
    public function testInvalidRemove() : void
    {
        self::assertFalse($this->header->remove('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Account data can be set and returned')]
    public function testAccountInputOutput() : void
    {
        $this->header->account = 2;
        self::assertEquals(2, $this->header->account);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Data can be defined as downloadable')]
    public function testDownloadable() : void
    {
        $this->header->setDownloadable('testname', 'mp3');
        self::assertEquals(MimeType::M_BIN, $this->header->get('Content-Type')[0]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A header can be locked')]
    public function testLockInputOutput() : void
    {
        $this->header->lock();
        self::assertTrue($this->header->isLocked());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A locked header cannot add new data')]
    public function testLockInvalidSet() : void
    {
        $this->header->lock();
        self::assertFalse($this->header->set('key', 'value'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A locked header cannot remove data')]
    public function testLockInvalidRemove() : void
    {
        $this->header->lock();
        self::assertFalse($this->header->remove('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The header can generate default http headers based on status codes')]
    public function testHeaderGeneration() : void
    {
        $consts = RequestStatusCode::getConstants();
        foreach ($consts as $status) {
            $this->header->generate($status);
            self::assertTrue(\stripos($this->header->get('status')[0], (string) $status) !== false, 'Failed for ' . $status);
        }
    }

    /*
    public function testGetAllHeaders() : void
    {
        $dummyHeaders = '{"REDIRECT_STATUS":"200","HTTP_HOST":"127.0.0.1","HTTP_CONNECTION":"keep-alive","HTTP_CACHE_CONTROL":"max-age=0","HTTP_SEC_CH_UA":"\" Not;A Brand\";v=\"99\", \"Google Chrome\";v=\"91\", \"Chromium\";v=\"91\"","HTTP_ACCEPT":"text\/html,application\/xhtml+xml,application\/xml;q=0.9,image\/avif,image\/webp,image\/apng,*\/*;q=0.8,application\/signed-exchange;v=b3;q=0.9","HTTP_UPGRADE_INSECURE_REQUESTS":"1","HTTP_SEC_CH_UA_MOBILE":"?0","HTTP_USER_AGENT":"Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/91.0.4472.114 Safari\/537.36","HTTP_SEC_FETCH_SITE":"same-origin","HTTP_SEC_FETCH_MODE":"same-origin","HTTP_SEC_FETCH_DEST":"empty","HTTP_ACCEPT_ENCODING":"gzip, deflate, br","HTTP_ACCEPT_LANGUAGE":"en-US,en;q=0.9","HTTP_COOKIE":"PHPSESSID=4olihfuke6ihkgpgkkluda9qm0","PATH":"\/usr\/local\/sbin:\/usr\/local\/bin:\/usr\/sbin:\/usr\/bin:\/sbin:\/bin:\/snap\/bin","SERVER_SIGNATURE":"Apache\/2.4.46 (Ubuntu) Server at 127.0.0.1 Port 80<\/address>\n","SERVER_SOFTWARE":"Apache\/2.4.46 (Ubuntu)","SERVER_NAME":"127.0.0.1","SERVER_ADDR":"127.0.0.1","SERVER_PORT":"80","REMOTE_ADDR":"127.0.0.1","DOCUMENT_ROOT":"\/home\/spl1nes\/Karaka","REQUEST_SCHEME":"http","CONTEXT_PREFIX":"","CONTEXT_DOCUMENT_ROOT":"\/home\/spl1nes\/Karaka","SERVER_ADMIN":"webmaster@localhost","SCRIPT_FILENAME":"\/home\/spl1nes\/Karaka\/index.php","REMOTE_PORT":"52430","REDIRECT_URL":"\/en\/backend","REDIRECT_QUERY_STRING":"{QUERY_STRING}","GATEWAY_INTERFACE":"CGI\/1.1","SERVER_PROTOCOL":"HTTP\/1.1","REQUEST_METHOD":"GET","QUERY_STRING":"{QUERY_STRING}","REQUEST_URI":"\/en\/backend","SCRIPT_NAME":"\/index.php","PHP_SELF":"\/index.php","REQUEST_TIME_FLOAT":1634157950.359451,"REQUEST_TIME":1634157950}';

        $tmp = $_SERVER;

        $_SERVER = \json_decode($dummyHeaders, true);
        self::assertEquals('127.0.0.1', $this->header->getAllHeaders()['host'] ?? '');

        // If headers are loaded once, only the cached version is used!
        $_SERVER = \json_decode('{"HTTP_HOST": "invalid"}', true);
        self::assertEquals('127.0.0.1', $this->header->getAllHeaders()['host'] ?? '');

        $_SERVER = $tmp;
    }
    */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Security header data cannot be changed once defined')]
    public function testInvalidOverwriteSecurityHeader() : void
    {
        self::assertTrue($this->header->set('content-security-policy', 'header'));
        self::assertFalse($this->header->set('content-security-policy', 'header', true));
    }
}
