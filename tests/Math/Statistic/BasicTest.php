<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\Basic;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Statistic\Basic::class)]
final class BasicTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
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
