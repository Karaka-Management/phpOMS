<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Topology;

use phpOMS\Math\Topology\Metrics2D;

/**
 * @testdox phpOMS\tests\Math\Topology\Metrics2DTest: Metric/distance calculations
 *
 * @internal
 */
final class Metrics2DTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The manhattan distance can be calculated
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testManhattan() : void
    {
        self::assertEquals(
            10.0,
            Metrics2D::manhattan(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    /**
     * @testdox The euclidean distance can be calculated
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testEuclidean() : void
    {
        self::assertEqualsWithDelta(
            7.615773,
            Metrics2D::euclidean(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    /**
     * @testdox The chebyshev distance can be calculated
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testChebyshev() : void
    {
        self::assertEquals(
            7.0,
            Metrics2D::chebyshev(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    /**
     * @testdox The octile distance can be calculated
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testOctile() : void
    {
        self::assertEqualsWithDelta(
            8.24264,
            Metrics2D::octile(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    /**
     * @testdox The minkowski distance can be calculated
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testMinkowski() : void
    {
        self::assertEqualsWithDelta(
            7.179,
            Metrics2D::minkowski(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], 3),
            0.1
        );
    }

    /**
     * @testdox The canberra distance can be calculated
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testCanberra() : void
    {
        self::assertEqualsWithDelta(
            1.333,
            Metrics2D::canberra(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    /**
     * @testdox The bray-curtis distance can be calculated
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testBrayCurtis() : void
    {
        self::assertEqualsWithDelta(
            0.625,
            Metrics2D::brayCurtis(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    /**
     * @testdox The angular distance can be calculated
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testAngularSeparation() : void
    {
        self::assertEqualsWithDelta(
            0.6508,
            Metrics2D::angularSeparation(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    /**
     * @testdox The hamming distance can be calculated
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testHammingDistance() : void
    {
        self::assertEquals(
            3,
            Metrics2D::hamming([1, 1, 1, 1], [0, 1, 0, 0]),
        );
    }

    /**
     * @testdox The ulam distance can be calculated
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testUlam() : void
    {
        self::assertEquals(
            2,
            Metrics2D::ulam([3, 6, 4, 8], [4, 6, 8, 3])
        );
    }

    /**
     * @testdox Different dimension sizes for the coordinates in the hamming metric throw a InvalidDimensionException
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testInvalidHammingDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        Metrics2D::hamming([1, 1, 1, 1], [0, 1, 0]);
    }

    /**
     * @testdox Different dimension sizes for the coordinates in the ulam metric throw a InvalidDimensionException
     * @covers phpOMS\Math\Topology\Metrics2D
     * @group framework
     */
    public function testInvalidUlamDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        Metrics2D::ulam([3, 6, 4], [4, 6, 8, 3]);
    }
}
