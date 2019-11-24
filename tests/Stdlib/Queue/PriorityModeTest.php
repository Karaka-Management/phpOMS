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

namespace phpOMS\tests\Stdlib\Queue;

use phpOMS\Stdlib\Queue\PriorityMode;

/**
 * @internal
 */
class PriorityModeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(4, PriorityMode::getConstants());
    }

    /**
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(1, PriorityMode::FIFO);
        self::assertEquals(2, PriorityMode::LIFO);
        self::assertEquals(4, PriorityMode::HIGHEST);
        self::assertEquals(8, PriorityMode::LOWEST);
    }
}
