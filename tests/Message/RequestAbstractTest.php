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

namespace phpOMS\tests\Message;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Message\RequestAbstract;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Message\RequestAbstract::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Message\RequestAbstractTest: Abstract request')]
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

            public function getRouteVerb() : int
            {
                return 0;
            }
        };
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Request data can be set and returned')]
    public function testDataInputOutput() : void
    {
        self::assertTrue($this->request->setData('key', 'value'));
        self::assertEquals('value', $this->request->getData('key'));
        self::assertTrue($this->request->hasData('key'));
        self::assertEquals(['key' => 'value'], $this->request->getData());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid data key returns null')]
    public function testInvalidDataKeyOutput() : void
    {
        self::assertNull($this->request->getData('invalid'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Request data can be set and returned with correct types')]
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

    public function testDataAllInputOutput() : void
    {
        $this->request->setData('asdf', false);
        self::assertEquals(['asdf' => false], $this->request->getData());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDataJsonInputOutput() : void
    {
        $this->request->setData('asdf', '[1,2,3]');
        self::assertEquals([1,2,3], $this->request->getDataJson('asdf'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDataStringInputOutput() : void
    {
        $this->request->setData('asdf', 1);
        self::assertEquals('1', $this->request->getDataString('asdf'));
        self::assertEquals('1', $this->request->getData('asdf', 'string'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDataBoolInputOutput() : void
    {
        $this->request->setData('asdf', 1);
        self::assertTrue($this->request->getDataBool('asdf'));
        self::assertTrue($this->request->getData('asdf', 'bool'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDataFloatInputOutput() : void
    {
        $this->request->setData('asdf', 1);
        self::assertEquals(1.0, $this->request->getDataFloat('asdf'));
        self::assertEquals(1.0, $this->request->getData('asdf', 'float'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDataDateTimeInputOutput() : void
    {
        $this->request->setData('asdf', '2023-01-01');
        self::assertEquals((new \DateTime('2023-01-01'))->format('Y-m-d'), $this->request->getDataDateTime('asdf')->format('Y-m-d'));
        self::assertEquals((new \DateTime('2023-01-01'))->format('Y-m-d'), $this->request->getData('asdf', 'DateTime')->format('Y-m-d'));
    }

    public function testDataInvalidTypeInputOutput() : void
    {
        $this->request->setData('asdf', 1);
        self::assertEquals(1, $this->request->getData('asdf', 'invalid'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidDataTypeInputOutput() : void
    {
        self::assertNull($this->request->getDataString('a'));
        self::assertNull($this->request->getDataBool('a'));
        self::assertNull($this->request->getDataInt('a'));
        self::assertNull($this->request->getDataFloat('a'));
        self::assertNull($this->request->getDataDateTime('a'));
    }
}
