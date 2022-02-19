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
use phpOMS\Math\Topology\MetricsND;

/**
 * @testdox phpOMS\tests\Math\Topology\MetricsNDTest: Metric/distance calculations
 *
 * @internal
 */
final class MetricsNDTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The manhattan distance can be calculated
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testManhattan() : void
    {
        self::assertEquals(
            MetricsND::manhattan(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::manhattan(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    /**
     * @testdox The euclidean distance can be calculated
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testEuclidean() : void
    {
        self::assertEqualsWithDelta(
            Metrics2D::euclidean(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::euclidean(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    /**
     * @testdox The chebyshev distance can be calculated
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testChebyshev() : void
    {
        self::assertEquals(
            MetricsND::chebyshev(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::chebyshev(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    /**
     * @testdox The minkowski distance can be calculated
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testMinkowski() : void
    {
        self::assertEqualsWithDelta(
            Metrics2D::minkowski(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], 3),
            MetricsND::minkowski(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], 3),
            0.1
        );
    }

    /**
     * @testdox The canberra distance can be calculated
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testCanberra() : void
    {
        self::assertEqualsWithDelta(
            Metrics2D::canberra(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::canberra(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    /**
     * @testdox The bray-curtis distance can be calculated
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testBrayCurtis() : void
    {
        self::assertEqualsWithDelta(
            Metrics2D::brayCurtis(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::brayCurtis(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    /**
     * @testdox The angular distance can be calculated
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testAngularSeparation() : void
    {
        self::assertEqualsWithDelta(
            Metrics2D::angularSeparation(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::angularSeparation(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    /**
     * @testdox The hamming distance can be calculated
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testHammingDistance() : void
    {
        self::assertEquals(
            MetricsND::hamming([1, 1, 1, 1], [0, 1, 0, 0]),
            MetricsND::hamming([1, 1, 1, 1], [0, 1, 0, 0]),
        );
    }

    /**
     * @testdox Different dimension sizes for the coordinates in the manhattan metric throw a InvalidDimensionException
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testInvalidManhattanDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::manhattan([3, 6, 4], [4, 6, 8, 3]);
    }

    /**
     * @testdox Different dimension sizes for the coordinates in the euclidean metric throw a InvalidDimensionException
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testInvalidEuclideanDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::euclidean([3, 6, 4], [4, 6, 8, 3]);
    }

    /**
     * @testdox Different dimension sizes for the coordinates in the chebyshev metric throw a InvalidDimensionException
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testInvalidChebyshevDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::chebyshev([3, 6, 4], [4, 6, 8, 3]);
    }

    /**
     * @testdox Different dimension sizes for the coordinates in the minkowski metric throw a InvalidDimensionException
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testInvalidMinkowskiDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::minkowski([3, 6, 4], [4, 6, 8, 3], 2);
    }

    /**
     * @testdox Different dimension sizes for the coordinates in the canberra metric throw a InvalidDimensionException
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testInvalidCanberraDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::canberra([3, 6, 4], [4, 6, 8, 3]);
    }

    /**
     * @testdox Different dimension sizes for the coordinates in the Bray Curtis metric throw a InvalidDimensionException
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testInvalidBrayCurtisDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::brayCurtis([3, 6, 4], [4, 6, 8, 3]);
    }

    /**
     * @testdox Different dimension sizes for the coordinates in the angular separation metric throw a InvalidDimensionException
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testInvalidAngularSeparationDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::angularSeparation([3, 6, 4], [4, 6, 8, 3]);
    }

    /**
     * @testdox Different dimension sizes for the coordinates in the hamming metric throw a InvalidDimensionException
     * @covers phpOMS\Math\Topology\MetricsND
     * @group framework
     */
    public function testInvalidHammingDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::hamming([3, 6, 4], [4, 6, 8, 3]);
    }
}
