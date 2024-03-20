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

namespace phpOMS\tests\Message;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Message\ResponseAbstract;

/**
 * @testdox phpOMS\tests\Message\ResponseAbstractTest: Abstract response
 *
 * @internal
 */
final class ResponseAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $response = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->response = new class() extends ResponseAbstract
        {
            public function toArray() : array
            {
                return [1];
            }

            public function getBody(bool $optimize = false) : string
            {
                return '';
            }
        };
    }

    /**
     * @testdox The response has the expected default values after initialization
     * @covers \phpOMS\Message\ResponseAbstract
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertNull($this->response->getData('asdf'));
        self::assertEquals('', $this->response->getBody());
    }

    /**
     * @testdox The response can be json serialized
     * @covers \phpOMS\Message\ResponseAbstract
     * @group framework
     */
    public function testJsonSerialize() : void
    {
        self::assertEquals([1], $this->response->jsonSerialize());
    }

    public function testDataAllInputOutput() : void
    {
        $this->response->set('asdf', false);
        self::assertEquals(['asdf' => false], $this->response->getData());
    }

    /**
     * @testdox Data can be set and returned for the response
     * @covers \phpOMS\Message\ResponseAbstract
     * @group framework
     */
    public function testDataInputOutput() : void
    {
        $this->response->set('asdf', false);
        self::assertFalse($this->response->getData('asdf'));
    }

    /**
     * @testdox Data can be set and returned for the response
     * @covers \phpOMS\Message\ResponseAbstract
     * @group framework
     */
    public function testDataStringInputOutput() : void
    {
        $this->response->set('asdf', 1);
        self::assertEquals('1', $this->response->getDataString('asdf'));
        self::assertEquals('1', $this->response->getData('asdf', 'string'));
    }

    /**
     * @testdox Data can be set and returned for the response
     * @covers \phpOMS\Message\ResponseAbstract
     * @group framework
     */
    public function testDataBoolInputOutput() : void
    {
        $this->response->set('asdf', 1);
        self::assertTrue($this->response->getDataBool('asdf'));
        self::assertTrue($this->response->getData('asdf', 'bool'));
    }

    /**
     * @testdox Data can be set and returned for the response
     * @covers \phpOMS\Message\ResponseAbstract
     * @group framework
     */
    public function testDataFloatInputOutput() : void
    {
        $this->response->set('asdf', 1);
        self::assertEquals(1.0, $this->response->getDataFloat('asdf'));
        self::assertEquals(1.0, $this->response->getData('asdf', 'float'));
    }

    /**
     * @group framework
     */
    public function testDataJsonInputOutput() : void
    {
        $this->response->set('asdf', '[1,2,3]');
        self::assertEquals([1,2,3], $this->response->getDataJson('asdf'));
    }

    /**
     * @testdox Data can be set and returned for the response
     * @covers \phpOMS\Message\ResponseAbstract
     * @group framework
     */
    public function testDataDateTimeInputOutput() : void
    {
        $this->response->set('asdf', '2023-01-01');
        self::assertEquals((new \DateTime('2023-01-01'))->format('Y-m-d'), $this->response->getDataDateTime('asdf')->format('Y-m-d'));
        self::assertEquals((new \DateTime('2023-01-01'))->format('Y-m-d'), $this->response->getData('asdf', 'DateTime')->format('Y-m-d'));
    }

    public function testDataInvalidTypeInputOutput() : void
    {
        $this->response->set('asdf', 1);
        self::assertEquals(1, $this->response->getData('asdf', 'invalid'));
    }

    /**
     * @testdox Data can be set and returned for the response
     * @covers \phpOMS\Message\ResponseAbstract
     * @group framework
     */
    public function testInvalidDataTypeInputOutput() : void
    {
        self::assertNull($this->response->getDataString('a'));
        self::assertNull($this->response->getDataBool('a'));
        self::assertNull($this->response->getDataInt('a'));
        self::assertNull($this->response->getDataFloat('a'));
        self::assertNull($this->response->getDataDateTime('a'));
    }
}
