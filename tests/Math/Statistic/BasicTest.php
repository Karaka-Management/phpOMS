<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\Basic;

/**
 * @internal
 */
final class BasicTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Statistic\Basic
     * @group framework
     */
    public function testFrequency() : void
    {
        self::assertEquals(
            [1 / 10, 2 / 10, 3 / 10, 4 / 10],
            Basic::frequency([1, 2, 3, 4])
        );

        self::assertEquals(
            [1 / 10, 2 / 10, 3 / 10, [1 / 6, 2 / 6, 3 / 6], 4 / 10],
            Basic::frequency([1, 2, 3, [1, 2, 3], 4])
        );
    }
}
