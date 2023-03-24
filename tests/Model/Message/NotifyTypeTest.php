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
        self::assertCount(5, NotifyType::getConstants());
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
        self::assertEquals(0, NotifyType::BINARY);
        self::assertEquals(1, NotifyType::INFO);
        self::assertEquals(2, NotifyType::WARNING);
        self::assertEquals(3, NotifyType::ERROR);
        self::assertEquals(4, NotifyType::FATAL);
    }
}
