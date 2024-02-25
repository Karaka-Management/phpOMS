<?php
/**
 * Jingga
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

namespace phpOMS\tests\Math\Geometry\ConvexHull;

use phpOMS\Math\Geometry\ConvexHull\GrahamScan;

/**
 * @testdox phpOMS\tests\Math\Geometry\ConvexHull\GrahamScanTest: Monotone chain
 *
 * @internal
 */
final class GrahamScanTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A convex hull can be formed from multiple points on a plane
     * @covers phpOMS\Math\Geometry\ConvexHull\GrahamScan
     * @group framework
     */
    public function testGrahamScan() : void
    {
        self::assertEquals([['x' => 9, 'y' => 0]], GrahamScan::createConvexHull([['x' => 9, 'y' => 0]]));

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
            GrahamScan::createConvexHull($points)
        );
    }
}
