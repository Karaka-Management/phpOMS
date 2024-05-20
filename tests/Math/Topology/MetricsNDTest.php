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

namespace phpOMS\tests\Math\Topology;

use phpOMS\Math\Topology\Metrics2D;
use phpOMS\Math\Topology\MetricsND;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Topology\MetricsND::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Topology\MetricsNDTest: Metric/distance calculations')]
final class MetricsNDTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The manhattan distance can be calculated')]
    public function testManhattan() : void
    {
        self::assertEquals(
            MetricsND::manhattan(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::manhattan(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The euclidean distance can be calculated')]
    public function testEuclidean() : void
    {
        self::assertEqualsWithDelta(
            Metrics2D::euclidean(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::euclidean(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The chebyshev distance can be calculated')]
    public function testChebyshev() : void
    {
        self::assertEquals(
            MetricsND::chebyshev(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::chebyshev(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The minkowski distance can be calculated')]
    public function testMinkowski() : void
    {
        self::assertEqualsWithDelta(
            Metrics2D::minkowski(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], 3),
            MetricsND::minkowski(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], 3),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The canberra distance can be calculated')]
    public function testCanberra() : void
    {
        self::assertEqualsWithDelta(
            Metrics2D::canberra(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::canberra(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The cosine distance can be calculated')]
    public function testCosine() : void
    {
        self::assertEqualsWithDelta(
            14 / 15,
            MetricsND::cosine(['x' => 3, 'y' => 4, 'z' => 0], ['x' => 4, 'y' => 4, 'z' => 2]),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The bray-curtis distance can be calculated')]
    public function testBrayCurtis() : void
    {
        self::assertEqualsWithDelta(
            Metrics2D::brayCurtis(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::brayCurtis(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The angular distance can be calculated')]
    public function testAngularSeparation() : void
    {
        self::assertEqualsWithDelta(
            Metrics2D::angularSeparation(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            MetricsND::angularSeparation(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The hamming distance can be calculated')]
    public function testHammingDistance() : void
    {
        self::assertEquals(
            MetricsND::hamming([1, 1, 1, 1], [0, 1, 0, 0]),
            MetricsND::hamming([1, 1, 1, 1], [0, 1, 0, 0]),
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for the coordinates in the manhattan metric throw a InvalidDimensionException')]
    public function testInvalidManhattanDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::manhattan([4, 6, 8, 3], [3, 6, 4]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for the coordinates in the euclidean metric throw a InvalidDimensionException')]
    public function testInvalidEuclideanDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::euclidean([4, 6, 8, 3], [3, 6, 4]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for the coordinates in the chebyshev metric throw a InvalidDimensionException')]
    public function testInvalidChebyshevDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::chebyshev([4, 6, 8, 3], [3, 6, 4]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for the coordinates in the minkowski metric throw a InvalidDimensionException')]
    public function testInvalidMinkowskiDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::minkowski([4, 6, 8, 3], [3, 6, 4], 2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for the coordinates in the canberra metric throw a InvalidDimensionException')]
    public function testInvalidCanberraDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::canberra([4, 6, 8, 3], [3, 6, 4]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for the coordinates in the cosine metric throw a InvalidDimensionException')]
    public function testInvalidCosineDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::cosine([4, 6, 8, 3], [3, 6, 4]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for the coordinates in the Bray Curtis metric throw a InvalidDimensionException')]
    public function testInvalidBrayCurtisDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::brayCurtis([4, 6, 8, 3], [3, 6, 4]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for the coordinates in the angular separation metric throw a InvalidDimensionException')]
    public function testInvalidAngularSeparationDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::angularSeparation([4, 6, 8, 3], [3, 6, 4]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for the coordinates in the hamming metric throw a InvalidDimensionException')]
    public function testInvalidHammingDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MetricsND::hamming([4, 6, 8, 3], [3, 6, 4]);
    }
}
