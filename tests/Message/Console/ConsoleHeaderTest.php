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
use phpOMS\Message\Console\ConsoleHeader;

/**
 * @internal
 */
class ConsoleHeaderTest extends \PHPUnit\Framework\TestCase
{
    private ConsoleHeader $header;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->header = new ConsoleHeader();
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleHeader
     * @group framework
     */
    public function testDefaults() : void
    {
        self::assertFalse($this->header->isLocked());
        self::assertEquals(0, $this->header->status);
        self::assertEquals('1.0', $this->header->getProtocolVersion());
        self::assertEquals('', $this->header->getReasonPhrase());
        self::assertEquals([], $this->header->get('key'));
        self::assertFalse($this->header->has('key'));
        self::assertInstanceOf(Localization::class, $this->header->l11n);
        self::assertEquals(0, $this->header->account);
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleHeader
     * @group framework
     */
    public function testValueInputOutput() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertEquals(['header'], $this->header->get('key'));
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleHeader
     * @group framework
     */
    public function testHasKey() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertTrue($this->header->has('key'));
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleHeader
     * @group framework
     */
    public function testInvalidOverwrite() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertFalse($this->header->set('key', 'header2'));
        self::assertEquals(['header'], $this->header->get('key'));
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleHeader
     * @group framework
     */
    public function testOverwrite() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertTrue($this->header->set('key', 'header3', true));
        self::assertEquals(['header3'], $this->header->get('key'));
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleHeader
     * @group framework
     */
    public function testRemove() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertTrue($this->header->remove('key'));
        self::assertFalse($this->header->has('key'));
        self::assertFalse($this->header->remove('key'));
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleHeader
     * @group framework
     */
    public function testAccount() : void
    {
        $this->header->account = 2;
        self::AssertEquals(2, $this->header->account);
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleHeader
     * @group framework
     */
    public function testLockedHeaderSet() : void
    {
        $this->header->lock();
        self::assertTrue($this->header->isLocked());
        self::assertFalse($this->header->set('key', 'value'));
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleHeader
     * @group framework
     */
    public function testLockedHeaderRemove() : void
    {
        $this->header->lock();
        self::assertTrue($this->header->isLocked());
        self::assertFalse($this->header->remove('key'));
    }
}
