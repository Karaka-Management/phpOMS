<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Message\Http;

use phpOMS\Message\Http\Response;
use phpOMS\System\MimeType;

/**
 * @internal
 */
class ResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $response = new Response();
        self::assertEquals('', $response->getBody());
        self::assertEquals('', $response->render());
        self::assertEquals([], $response->toArray());
        self::assertInstanceOf('\phpOMS\Localization\Localization', $response->getHeader()->getL11n());
        self::assertInstanceOf('\phpOMS\Message\Http\Header', $response->getHeader());
    }

    public function testSetGet() : void
    {
        $response = new Response();

        $response->setResponse(['a' => 1]);
        self::assertTrue($response->remove('a'));
        self::assertFalse($response->remove('a'));
    }

    public function testWithData() : void
    {
        $response = new Response();

        $data = [
            ['view_string'],
            [1, 2, 3, 'a', 'b', [4, 5]],
            'stringVal',
            6,
            false,
            1.13,
            'json_string',
        ];

        $response->set('view', new class() extends \phpOMS\Views\View {
            public function toArray() : array
            {
                return ['view_string'];
            }
        });
        $response->set('array', $data[1]);
        $response->set('string', $data[2]);
        $response->set('int', $data[3]);
        $response->set('bool', $data[4]);
        $response->set('float', $data[5]);
        $response->set('jsonSerializable', new class() implements \JsonSerializable {
            public function jsonSerialize()
            {
                return 'json_string';
            }
        });
        $response->set('null', null);

        self::assertEquals($data, $response->toArray());

        $response->getHeader()->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        self::assertEquals(\json_encode($data), $response->render());
    }

    public function testMinimizedRender() : void
    {
        $response = new Response();

        $response->set('view', new class() extends \phpOMS\Views\View {
            public function render(...$data) : string
            {
                return " view_string  with <div> text</div>  that has \n whitespaces and \n\nnew lines\n ";
            }
        });

        $response->getHeader()->set('Content-Type', MimeType::M_HTML . '; charset=utf-8', true);
        self::assertEquals('view_string with <div> text</div> that has whitespaces and new lines', $response->render(true));
    }

    public function testInvalidResponseData() : void
    {
        $response = new Response();
        $response->set('invalid', new class() {});
        self::assertEquals([], $response->toArray());
    }
}
