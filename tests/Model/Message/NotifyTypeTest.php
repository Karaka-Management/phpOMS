<?php
/**
 * Karaka
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

namespace phpOMS\tests\phpOMS\Model\Message;

use phpOMS\Model\Message\NotifyType;

/**
 * @internal
 */
final class NotifyTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(7, NotifyType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(NotifyType::getConstants(), \array_unique(NotifyType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals('binary', NotifyType::BINARY);
        self::assertEquals('info', NotifyType::INFO);
        self::assertEquals('warning', NotifyType::WARNING);
        self::assertEquals('error', NotifyType::ERROR);
        self::assertEquals('fatal', NotifyType::FATAL);
    }
}
