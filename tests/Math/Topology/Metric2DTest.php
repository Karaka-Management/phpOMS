<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Topology\Metric2D;

/**
 * @internal
 */
class Metric2DTest extends \PHPUnit\Framework\TestCase
{
    public function testManhattan() : void
    {
        self::assertEquals(
            10.0,
            Metric2D::manhattan(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    public function testEuclidean() : void
    {
        self::assertEqualsWithDelta(
            7.615773,
            Metric2D::euclidean(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    public function testChebyshev() : void
    {
        self::assertEquals(
            7.0,
            Metric2D::chebyshev(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    public function testMinkowski() : void
    {
        self::assertEqualsWithDelta(
            7.179,
            Metric2D::minkowski(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], 3),
            0.1
        );
    }

    public function testCanberra() : void
    {
        self::assertEqualsWithDelta(
            1.333,
            Metric2D::canberra(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    public function testBrayCurtis() : void
    {
        self::assertEqualsWithDelta(
            0.625,
            Metric2D::brayCurtis(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    public function testAngularSeparation() : void
    {
        self::assertEqualsWithDelta(
            0.6508,
            Metric2D::angularSeparation(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    public function testHammingDistance() : void
    {
        self::assertEquals(
            3.0,
            Metric2D::hammingDistance([1, 1, 1, 1], [0, 1, 0, 0]),
        );
    }

    public function testUlam(): void
    {
        self::assertEquals(
            2,
            Metric2D::hammingDistance([3, 6, 4, 8], [4, 6, 8, 3])
        );
    }
}
