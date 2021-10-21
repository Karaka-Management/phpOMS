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

namespace phpOMS\tests\Message;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Message\RequestAbstract;

/**
 * @testdox phpOMS\tests\Message\RequestAbstractTest: Abstract request
 *
 * @internal
 */
final class RequestAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $request = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->request = new class() extends RequestAbstract
        {
            public function getOrigin() : string
            {
                return '';
            }

            public function getBody(bool $optimize = false) : string
            {
                return '';
            }
        };
    }

    /**
     * @testdox Request data can be set and returned
     * @covers phpOMS\Message\RequestAbstract
     * @group framework
     */
    public function testDataInputOutput() : void
    {
        self::assertTrue($this->request->setData('key', 'value'));
        self::assertEquals('value', $this->request->getData('key'));
        self::assertTrue($this->request->hasData('key'));
        self::assertEquals(['key' => 'value'], $this->request->getData());
    }

    /**
     * @testdox A invalid data key returns null
     * @covers phpOMS\Message\RequestAbstract
     * @group framework
     */
    public function testInvalidDataKeyOutput() : void
    {
        self::assertNull($this->request->getData('invalid'));
    }

    /**
     * @testdox Request data can be set and returned with correct types
     * @covers phpOMS\Message\RequestAbstract
     * @group framework
     */
    public function testDataTypeInputOutput() : void
    {
        $this->request->setData('key1', 1);
        self::assertEquals('1', $this->request->getData('key1', 'string'));

        $this->request->setData('key2', '2');
        self::assertEquals(2, $this->request->getData('key2', 'int'));

        $this->request->setData('key3', '1');
        self::assertTrue($this->request->getData('key3', 'bool'));

        $this->request->setData('key4', '1.23');
        self::assertEquals(1.23, $this->request->getData('key4', 'float'));

        $this->request->setData('key5', 1);
        self::assertEquals(1, $this->request->getData('key5', 'invalid'));
    }
}
