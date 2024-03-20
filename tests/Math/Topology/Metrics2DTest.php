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

namespace phpOMS\tests\Math\Topology;

use phpOMS\Math\Topology\Metrics2D;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Topology\Metrics2D::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Topology\Metrics2DTest: Metric/distance calculations')]
final class Metrics2DTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The manhattan distance can be calculated')]
    public function testManhattan() : void
    {
        self::assertEquals(
            10.0,
            Metrics2D::manhattan(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The euclidean distance can be calculated')]
    public function testEuclidean() : void
    {
        self::assertEqualsWithDelta(
            7.615773,
            Metrics2D::euclidean(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The chebyshev distance can be calculated')]
    public function testChebyshev() : void
    {
        self::assertEquals(
            7.0,
            Metrics2D::chebyshev(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The octile distance can be calculated')]
    public function testOctile() : void
    {
        self::assertEqualsWithDelta(
            8.24264,
            Metrics2D::octile(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The minkowski distance can be calculated')]
    public function testMinkowski() : void
    {
        self::assertEqualsWithDelta(
            7.179,
            Metrics2D::minkowski(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], 3),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The canberra distance can be calculated')]
    public function testCanberra() : void
    {
        self::assertEqualsWithDelta(
            1.333,
            Metrics2D::canberra(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The bray-curtis distance can be calculated')]
    public function testBrayCurtis() : void
    {
        self::assertEqualsWithDelta(
            0.625,
            Metrics2D::brayCurtis(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The angular distance can be calculated')]
    public function testAngularSeparation() : void
    {
        self::assertEqualsWithDelta(
            0.6508,
            Metrics2D::angularSeparation(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6]),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The hamming distance can be calculated')]
    public function testHammingDistance() : void
    {
        self::assertEquals(
            3,
            Metrics2D::hamming([1, 1, 1, 1], [0, 1, 0, 0]),
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The ulam distance can be calculated')]
    public function testUlam() : void
    {
        self::assertEquals(
            2,
            Metrics2D::ulam([3, 6, 4, 8], [4, 6, 8, 3])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for the coordinates in the hamming metric throw a InvalidDimensionException')]
    public function testInvalidHammingDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        Metrics2D::hamming([1, 1, 1, 1], [0, 1, 0]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for the coordinates in the ulam metric throw a InvalidDimensionException')]
    public function testInvalidUlamDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        Metrics2D::ulam([3, 6, 4], [4, 6, 8, 3]);
    }
}
