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

use phpOMS\Math\Topology\Kernel2D;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Topology\Kernel2DTest: Metric/distance calculations')]
final class Kernel2DTest extends \PHPUnit\Framework\TestCase
{
    public function testUniform() : void
    {
        self::assertEquals(0.5, Kernel2D::uniformKernel(0, 2));
        self::assertEquals(0.5, Kernel2D::uniformKernel(-1, 2));
        self::assertEquals(0.5, Kernel2D::uniformKernel(1, 2));

        self::assertEquals(0.0, Kernel2D::uniformKernel(2, 2));
        self::assertEquals(0.0, Kernel2D::uniformKernel(-2, 2));
    }

    public function testTriangle() : void
    {
        self::assertEquals(1.0, Kernel2D::triangularKernel(0, 2));
        self::assertEquals(0.0, Kernel2D::triangularKernel(-1, 2));
        self::assertEquals(0.0, Kernel2D::triangularKernel(1, 2));

        self::assertEquals(0.0, Kernel2D::triangularKernel(2, 2));
        self::assertEquals(0.0, Kernel2D::triangularKernel(-2, 2));
    }

    public function testEpanechnikov() : void
    {
        self::assertEquals(3 / 4, Kernel2D::epanechnikovKernel(0, 2));
        self::assertEquals(0.0, Kernel2D::epanechnikovKernel(-1, 2));
        self::assertEquals(0.0, Kernel2D::epanechnikovKernel(1, 2));

        self::assertEquals(0.0, Kernel2D::epanechnikovKernel(2, 2));
        self::assertEquals(0.0, Kernel2D::epanechnikovKernel(-2, 2));
    }

    public function testQuartic() : void
    {
        self::assertEquals(15 / 16, Kernel2D::quarticKernel(0, 2));
        self::assertEquals(0.0, Kernel2D::quarticKernel(-1, 2));
        self::assertEquals(0.0, Kernel2D::quarticKernel(1, 2));

        self::assertEquals(0.0, Kernel2D::quarticKernel(2, 2));
        self::assertEquals(0.0, Kernel2D::quarticKernel(-2, 2));
    }

    public function testTriweight() : void
    {
        self::assertEquals(35 / 32, Kernel2D::triweightKernel(0, 2));
        self::assertEquals(0.0, Kernel2D::triweightKernel(-1, 2));
        self::assertEquals(0.0, Kernel2D::triweightKernel(1, 2));

        self::assertEquals(0.0, Kernel2D::triweightKernel(2, 2));
        self::assertEquals(0.0, Kernel2D::triweightKernel(-2, 2));
    }

    public function testTricube() : void
    {
        self::assertEquals(70 / 81, Kernel2D::tricubeKernel(0, 2));
        self::assertEquals(0.0, Kernel2D::tricubeKernel(-1, 2));
        self::assertEquals(0.0, Kernel2D::tricubeKernel(1, 2));

        self::assertEquals(0.0, Kernel2D::tricubeKernel(2, 2));
        self::assertEquals(0.0, Kernel2D::tricubeKernel(-2, 2));
    }

    public function testGaussian() : void
    {
        self::assertEqualsWithDelta(1 / \sqrt(2 * \M_PI), Kernel2D::gaussianKernel(0, 2), 0.001);
        self::assertEqualsWithDelta(0.24197072451914, Kernel2D::gaussianKernel(-1, 2), 0.001);
        self::assertEqualsWithDelta(0.24197072451914, Kernel2D::gaussianKernel(1, 2), 0.001);

        self::assertEqualsWithDelta(0.004431848411938, Kernel2D::gaussianKernel(3, 2), 0.001);
        self::assertEqualsWithDelta(0.004431848411938, Kernel2D::gaussianKernel(-3, 2), 0.001);
    }

    public function testCosine() : void
    {
        self::assertEqualsWithDelta(\M_PI / 4, Kernel2D::cosineKernel(0, 2), 0.001);
        self::assertEqualsWithDelta(0.0, Kernel2D::cosineKernel(-1, 2), 0.001);
        self::assertEqualsWithDelta(0.0, Kernel2D::cosineKernel(1, 2), 0.001);

        self::assertEqualsWithDelta(0.0, Kernel2D::cosineKernel(2, 2), 0.001);
        self::assertEqualsWithDelta(0.0, Kernel2D::cosineKernel(-2, 2), 0.001);
    }

    public function testLogistic() : void
    {
        self::assertEqualsWithDelta(0.25, Kernel2D::logisticKernel(0, 2), 0.001);
        self::assertEqualsWithDelta(0.19661193324148, Kernel2D::logisticKernel(-1, 2), 0.001);
        self::assertEqualsWithDelta(0.19661193324148, Kernel2D::logisticKernel(1, 2), 0.001);

        self::assertEqualsWithDelta(0.10499358540351, Kernel2D::logisticKernel(2, 2), 0.001);
        self::assertEqualsWithDelta(0.10499358540351, Kernel2D::logisticKernel(-2, 2), 0.001);
    }
}
