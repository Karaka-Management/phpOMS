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

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Topology\Metrics2D;

/**
 * @internal
 */
class Metrics2DTest extends \PHPUnit\Framework\TestCase
{
    public function testManhattan() : void
    {
        self::assertEquals(
            10.0,
            Metrics2D::manhattan(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    public function testEuclidean() : void
    {
        self::assertEqualsWithDelta(
            7.615773,
            Metrics2D::euclidean(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    public function testChebyshev() : void
    {
        self::assertEquals(
            7.0,
            Metrics2D::chebyshev(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    public function testMinkowski() : void
    {
        self::assertEqualsWithDelta(
            7.179,
            Metrics2D::minkowski(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], 3),
            0.1
        );
    }

    public function testCanberra() : void
    {
        self::assertEqualsWithDelta(
            1.333,
            Metrics2D::canberra(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    public function testBrayCurtis() : void
    {
        self::assertEqualsWithDelta(
            0.625,
            Metrics2D::brayCurtis(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    public function testAngularSeparation() : void
    {
        self::assertEqualsWithDelta(
            0.6508,
            Metrics2D::angularSeparation(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    public function testHammingDistance() : void
    {
        self::assertEquals(
            3,
            Metrics2D::hamming([1, 1, 1, 1], [0, 1, 0, 0]),
        );
    }

    public function testUlam(): void
    {
        self::assertEquals(
            2,
            Metrics2D::ulam([3, 6, 4, 8], [4, 6, 8, 3])
        );
    }

    public function testInvalidHammingDimension() : void
    {
        self::expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        Metrics2D::hamming([1, 1, 1, 1], [0, 1, 0]);
    }

    public function testInvalidUlamDimension() : void
    {
        self::expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        Metrics2D::ulam([3, 6, 4], [4, 6, 8, 3]);
    }
}
