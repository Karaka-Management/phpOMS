<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Message\Http;

use phpOMS\Message\Http\HttpResponse;
use phpOMS\System\MimeType;

/**
 * @testdox phpOMS\tests\Message\Http\ResponseTest: HttpResponse wrapper for http responses
 *
 * @internal
 */
final class HttpResponseTest extends \PHPUnit\Framework\TestCase
{
    protected HttpResponse $response;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->response = new HttpResponse();
    }

    /**
     * @testdox The response has the expected default values after initialization
     * @covers phpOMS\Message\Http\HttpResponse<extended>
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals('', $this->response->getBody());
        self::assertEquals('', $this->response->render());
        self::assertEquals([], $this->response->toArray());
        self::assertInstanceOf('\phpOMS\Localization\Localization', $this->response->header->l11n);
        self::assertInstanceOf('\phpOMS\Message\Http\HttpHeader', $this->response->header);
    }

    /**
     * @testdox Response data can be set and returned
     * @covers phpOMS\Message\Http\HttpResponse<extended>
     * @group framework
     */
    public function testResponseInputOutput() : void
    {
        $this->response->setResponse(['a' => 1]);
        self::assertEquals(1, $this->response->getData('a'));
    }

    /**
     * @testdox Response data can be removed
     * @covers phpOMS\Message\Http\HttpResponse<extended>
     * @group framework
     */
    public function testRemove() : void
    {
        $this->response->setResponse(['a' => 1]);
        self::assertTrue($this->response->remove('a'));
    }

    /**
     * @testdox None-existing response data cannot be removed
     * @covers phpOMS\Message\Http\HttpResponse<extended>
     * @group framework
     */
    public function testInvalidRemove() : void
    {
        $this->response->setResponse(['a' => 1]);
        $this->response->remove('a');

        self::assertFalse($this->response->remove('a'));
    }

    /**
     * @testdox Test disabling output buffering
     * @covers phpOMS\Message\Http\HttpResponse
     * @group framework
     */
    public function testEndAllOutputBuffering() : void
    {
        if (\headers_sent()) {
            $this->response->header->lock();
        }
        $start = \ob_get_level();

        \ob_start();
        \ob_start();

        self::assertEquals($start + 2, $end = \ob_get_level());
        $this->response->endAllOutputBuffering($end - $start);
        self::assertEquals($start, \ob_get_level());
    }

    /**
     * @testdox Response data can be turned into an array
     * @covers phpOMS\Message\Http\HttpResponse<extended>
     * @group framework
     */
    public function testToArray() : void
    {
        $data = [
            ['view_string'],
            [1, 2, 3, 'a', 'b', [4, 5]],
            'stringVal',
            6,
            false,
            1.13,
            'json_string',
        ];

        $this->response->set('view', new class() extends \phpOMS\Views\View {
            public function toArray() : array
            {
                return ['view_string'];
            }
        });
        $this->response->set('array', $data[1]);
        $this->response->set('string', $data[2]);
        $this->response->set('int', $data[3]);
        $this->response->set('bool', $data[4]);
        $this->response->set('float', $data[5]);
        $this->response->set('jsonSerializable', new class() implements \JsonSerializable {
            public function jsonSerialize() : mixed
            {
                return 'json_string';
            }
        });
        $this->response->set('null', null);

        self::assertEquals($data, $this->response->toArray());
    }

    /**
     * @testdox A response with json as content-type is automatically rendered as json data
     * @covers phpOMS\Message\Http\HttpResponse<extended>
     * @group framework
     */
    public function testJsonRender() : void
    {
        $data = [
            ['view_string'],
            [1, 2, 3, 'a', 'b', [4, 5]],
            'stringVal',
            6,
            false,
            1.13,
            'json_string',
        ];

        $this->response->set('view', new class() extends \phpOMS\Views\View {
            public function toArray() : array
            {
                return ['view_string'];
            }
        });
        $this->response->set('array', $data[1]);
        $this->response->set('string', $data[2]);
        $this->response->set('int', $data[3]);
        $this->response->set('bool', $data[4]);
        $this->response->set('float', $data[5]);
        $this->response->set('jsonSerializable', new class() implements \JsonSerializable {
            public function jsonSerialize() : mixed
            {
                return 'json_string';
            }
        });
        $this->response->set('null', null);

        $this->response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        self::assertEquals(\json_encode($data), $this->response->render());
    }

    /**
     * @testdox Json data can be decoded from the response data
     * @covers phpOMS\Message\Http\HttpResponse<extended>
     * @group framework
     */
    public function testJsonDataDecode() : void
    {
        $array = [1, 'abc' => 'def'];
        $this->response->set('json', \json_encode($array));

        self::assertEquals($array, $this->response->getJsonData());
    }

    /**
     * @testdox A html response can be forced to minimize the content by removing newlines and whitespaces
     * @covers phpOMS\Message\Http\HttpResponse<extended>
     * @group framework
     */
    public function testMinimizedRender() : void
    {
        $this->response->set('view', new class() extends \phpOMS\Views\View {
            public function render(mixed ...$data) : string
            {
                return " view_string  with <div> text</div>  that has \n whitespaces and \n\nnew lines\n ";
            }
        });

        $this->response->header->set('Content-Type', MimeType::M_HTML . '; charset=utf-8', true);
        self::assertEquals('view_string with <div> text</div> that has whitespaces and new lines', $this->response->render(true));
    }

    /**
     * @testdox None-html responses cannot be forced to minimize the content by removing newlines and whitespaces
     * @covers phpOMS\Message\Http\HttpResponse<extended>
     * @group framework
     */
    public function testInvalidMinimizedRender() : void
    {
        $this->response->set('view', new class() extends \phpOMS\Views\View {
            public function render(mixed ...$data) : string
            {
                return " view_string  with <div> text</div>  that has \n whitespaces and \n\nnew lines\n ";
            }
        });

        $this->response->header->set('Content-Type', MimeType::M_TEXT . '; charset=utf-8', true);
        self::assertEquals(" view_string  with <div> text</div>  that has \n whitespaces and \n\nnew lines\n ", $this->response->render(true));
    }

    /**
     * @testdox Invalid response data results in an empty array
     * @covers phpOMS\Message\Http\HttpResponse<extended>
     * @group framework
     */
    public function testInvalidResponseDataToArray() : void
    {
        $this->response->set('invalid', new class() {});
        self::assertEquals([], $this->response->toArray());
    }

    /**
     * @testdox Invalid response data results in an empty render
     * @covers phpOMS\Message\Http\HttpResponse<extended>
     * @group framework
     */
    public function testInvalidResponseDataRender() : void
    {
        $this->response->set('invalid', new class() {});
        self::assertEquals('', $this->response->render());
    }
}
