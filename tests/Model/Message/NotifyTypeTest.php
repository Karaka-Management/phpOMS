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

namespace phpOMS\tests\phpOMS\Model\Message;

use phpOMS\Model\Message\NotifyType;

/**
 * @internal
 */
class NotifyTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertCount(5, NotifyType::getConstants());
        self::assertEquals(NotifyType::getConstants(), \array_unique(NotifyType::getConstants()));

        self::assertEquals(0, NotifyType::BINARY);
        self::assertEquals(1, NotifyType::INFO);
        self::assertEquals(2, NotifyType::WARNING);
        self::assertEquals(3, NotifyType::ERROR);
        self::assertEquals(4, NotifyType::FATAL);
    }
}
