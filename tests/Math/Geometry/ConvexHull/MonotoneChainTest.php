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

namespace phpOMS\tests\Math\Geometry\ConvexHull;

use phpOMS\Math\Geometry\ConvexHull\MonotoneChain;

/**
 * @internal
 */
class MonotoneChainTest extends \PHPUnit\Framework\TestCase
{
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
