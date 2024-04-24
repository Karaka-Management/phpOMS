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

namespace phpOMS\tests\Math\Geometry\ConvexHull;

use phpOMS\Math\Geometry\ConvexHull\MonotoneChain;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Geometry\ConvexHull\MonotoneChain::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Geometry\ConvexHull\MonotoneChainTest: Monotone chain')]
final class MonotoneChainTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A convex hull can be formed from multiple points on a plane')]
    public function testMonotoneChain() : void
    {
        self::assertEquals([['x' => 9, 'y' => 0]], MonotoneChain::createConvexHull([['x' => 9, 'y' => 0]]));

        $points = [];
        for ($i = 0; $i < 10; ++$i) {
            for ($j = 0; $j < 10; ++$j) {
                $points[] = ['x' => $i, 'y' => $j];
            }
        }

        self::assertEquals([
                ['x' => 0, 'y' => 0],
                ['x' => 9, 'y' => 0],
                ['x' => 9, 'y' => 9],
                ['x' => 0, 'y' => 9],
            ],
            MonotoneChain::createConvexHull($points)
        );
    }
}
