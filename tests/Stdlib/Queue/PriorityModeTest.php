<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
 declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Queue;

use phpOMS\Stdlib\Queue\PriorityMode;

/**
 * @internal
 */
class PriorityModeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(4, PriorityMode::getConstants());
        self::assertEquals(1, PriorityMode::FIFO);
        self::assertEquals(2, PriorityMode::LIFO);
        self::assertEquals(4, PriorityMode::HIGHEST);
        self::assertEquals(8, PriorityMode::LOWEST);
    }
}
