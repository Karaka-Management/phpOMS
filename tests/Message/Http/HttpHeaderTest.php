<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Message\Http;

use phpOMS\Localization\Localization;
use phpOMS\Message\Http\HttpHeader;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\System\MimeType;

/**
 * @testdox phpOMS\tests\Message\Http\HttpHeaderTest: Header for http requests/responses
 *
 * @internal
 */
class HttpHeaderTest extends \PHPUnit\Framework\TestCase
{
    protected HttpHeader $header;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->header = new HttpHeader();
    }

    /**
     * @testdox The header has the expected default values after initialization
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testDefaults() : void
    {
        self::assertFalse($this->header->isLocked());
        self::assertEquals(RequestStatusCode::R_200, $this->header->status);
        self::assertEquals('HTTP/1.1', $this->header->getProtocolVersion());
        self::assertEmpty(HttpHeader::getAllHeaders());
        self::assertEquals('', $this->header->getReasonPhrase());
        self::assertEquals([], $this->header->get('key'));
        self::assertFalse($this->header->has('key'));
        self::assertInstanceOf(Localization::class, $this->header->l11n);
        self::assertEquals(0, $this->header->account);
    }

    /**
     * @testdox Security policy headers get correctly identified
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testSecurityHeader() : void
    {
        self::assertTrue(HttpHeader::isSecurityHeader('content-security-policy'));
        self::assertTrue(HttpHeader::isSecurityHeader('X-xss-protection'));
        self::assertTrue(HttpHeader::isSecurityHeader('x-conTent-tYpe-options'));
        self::assertTrue(HttpHeader::isSecurityHeader('x-frame-options'));
        self::assertFalse(HttpHeader::isSecurityHeader('x-frame-optionss'));
    }

    /**
     * @testdox Header data can be set, checked for existence and returned
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testDataInputOutput() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertEquals(['header'], $this->header->get('key'));
        self::assertTrue($this->header->has('key'));
    }

    /**
     * @testdox Header data can be forced to get overwritten
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testOverwrite() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertTrue($this->header->set('key', 'header3', true));
        self::assertEquals(['header3'], $this->header->get('key'));
    }

    /**
     * @testdox By default header data doesn't get overwritten
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testInvalidOverwrite() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertFalse($this->header->set('key', 'header2'));
        self::assertEquals(['header'], $this->header->get('key'));
    }

    /**
     * @testdox Header data can be removed
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testRemove() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertTrue($this->header->remove('key'));
        self::assertFalse($this->header->has('key'));
    }

    /**
     * @testdox None-existing header data cannot be removed
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testInvalidRemove() : void
    {
        self::assertFalse($this->header->remove('key'));
    }

    /**
     * @testdox Account data can be set and returned
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testAccountInputOutput() : void
    {
        $this->header->account = 2;
        self::assertEquals(2, $this->header->account);
    }

    /**
     * @testdox Data can be defined as downloadable
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testDownloadable() : void
    {
        $this->header->setDownloadable('testname', 'mp3');
        self::assertEquals(MimeType::M_BIN, $this->header->get('Content-Type')[0]);
    }

    /**
     * @testdox A header can be locked
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testLockInputOutput() : void
    {
        $this->header->lock();
        self::assertTrue($this->header->isLocked());
    }

    /**
     * @testdox A locked header cannot add new data
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testLockInvalidSet() : void
    {
        $this->header->lock();
        self::assertFalse($this->header->set('key', 'value'));
    }

    /**
     * @testdox A locked header cannot remove data
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testLockInvalidRemove() : void
    {
        $this->header->lock();
        self::assertFalse($this->header->remove('key'));
    }

    /**
     * @testdox The header can generate default http headers based on status codes
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testHeaderGeneration() : void
    {
        $this->header->generate(RequestStatusCode::R_403);
        self::assertEquals(403, http_response_code());

        $this->header->generate(RequestStatusCode::R_404);
        self::assertEquals(404, http_response_code());

        $this->header->generate(RequestStatusCode::R_406);
        self::assertEquals(406, http_response_code());

        $this->header->generate(RequestStatusCode::R_407);
        self::assertEquals(407, http_response_code());

        $this->header->generate(RequestStatusCode::R_503);
        self::assertEquals(503, http_response_code());

        $this->header->generate(RequestStatusCode::R_500);
        self::assertEquals(500, http_response_code());
    }

    /**
     * @testdox Security header data cannot be changed once defined
     * @covers phpOMS\Message\Http\HttpHeader<extended>
     * @group framework
     */
    public function testInvalidOverwriteSecurityHeader() : void
    {
        self::assertTrue($this->header->set('content-security-policy', 'header'));
        self::assertFalse($this->header->set('content-security-policy', 'header', true));
    }
}
