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

namespace phpOMS\tests\Message\Console;

use phpOMS\Localization\Localization;
use phpOMS\Message\Console\ConsoleHeader;

/**
 * @internal
 */
class ConsoleHeaderTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaults() : void
    {
        $header = new ConsoleHeader();
        self::assertFalse($header->isLocked());
        self::assertEquals(0, $header->getStatusCode());
        self::assertEquals('1.0', $header->getProtocolVersion());
        self::assertEquals('', $header->getReasonPhrase());
        self::assertEquals([], $header->get('key'));
        self::assertFalse($header->has('key'));
        self::assertInstanceOf(Localization::class, $header->getL11n());
        self::assertEquals(0, $header->getAccount());
    }

    public function testGetSet() : void
    {
        $header = new ConsoleHeader();

        self::assertTrue($header->set('key', 'header'));
        self::assertEquals(['header'], $header->get('key'));
        self::assertTrue($header->has('key'));

        self::assertFalse($header->set('key', 'header2'));
        self::assertEquals(['header'], $header->get('key'));

        self::assertTrue($header->set('key', 'header3', true));
        self::assertEquals(['header3'], $header->get('key'));

        self::assertTrue($header->remove('key'));
        self::assertFalse($header->has('key'));
        self::assertFalse($header->remove('key'));

        $header->setAccount(2);
        self::AssertEquals(2, $header->getAccount(2));
    }

    public function testLockedHeaderSet() : void
    {
        $header = new ConsoleHeader();
        $header->lock();
        self::assertTrue($header->isLocked());
        self::assertFalse($header->set('key', 'value'));
    }

    public function testLockedHeaderRemove() : void
    {
        $header = new ConsoleHeader();
        $header->lock();
        self::assertTrue($header->isLocked());
        self::assertFalse($header->remove('key'));
    }
}
