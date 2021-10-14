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

namespace phpOMS\tests\Message\Console;

use phpOMS\Localization\Localization;
use phpOMS\Message\Console\ConsoleResponse;
use phpOMS\System\MimeType;

/**
 * @internal
 */
class ConsoleResponseTest extends \PHPUnit\Framework\TestCase
{
    protected ConsoleResponse $response;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->response = new ConsoleResponse();
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleResponse
     * @group framework
     */
    public function testDefault() : void
    {
        $this->response = new ConsoleResponse(new Localization());
        self::assertEquals('', $this->response->getBody());
        self::assertEquals('', $this->response->render());
        self::assertEquals([], $this->response->toArray());
        self::assertInstanceOf('\phpOMS\Localization\Localization', $this->response->header->l11n);
        self::assertInstanceOf('\phpOMS\Message\Console\ConsoleHeader', $this->response->header);
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleResponse
     * @group framework
     */
    public function testSetGet() : void
    {
        $this->response = new ConsoleResponse(new Localization());

        $this->response->setResponse(['a' => 1]);
        self::assertTrue($this->response->remove('a'));
        self::assertFalse($this->response->remove('a'));
    }

    /**
     * @testdox Response data can be turned into an array
     * @covers phpOMS\Message\Console\ConsoleResponse<extended>
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
            public function jsonSerialize()
            {
                return 'json_string';
            }
        });
        $this->response->set('null', null);

        self::assertEquals($data, $this->response->toArray());
    }

    /**
     * @testdox A response with json as content-type is automatically rendered as json data
     * @covers phpOMS\Message\Console\ConsoleResponse<extended>
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
            public function jsonSerialize()
            {
                return 'json_string';
            }
        });
        $this->response->set('null', null);

        $this->response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        self::assertEquals(\json_encode($data), $this->response->render());
    }

    /**
     * @testdox Invalid response data results in an empty array
     * @covers phpOMS\Message\Console\ConsoleResponse<extended>
     * @group framework
     */
    public function testInvalidResponseDataToArray() : void
    {
        $this->response->set('invalid', new class() {});
        self::assertEquals([], $this->response->toArray());
    }

    /**
     * @testdox Invalid response data results in an empty render
     * @covers phpOMS\Message\Console\ConsoleResponse<extended>
     * @group framework
     */
    public function testInvalidResponseDataRender() : void
    {
        $this->response->set('invalid', new class() {});
        self::assertEquals('', $this->response->render());
    }
}
