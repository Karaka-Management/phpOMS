<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\CauchyDistribution;

class CauchyDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testMedianMode()
    {
        self::assertEquals(3.2, CauchyDistribution::getMedian(3.2));
        self::assertEquals(3.2, CauchyDistribution::getMode(3.2));
    }

    public function testPdf()
    {
        $x     = 1;
        $x0    = 0.5;
        $gamma = 2;

        self::assertEquals(0.14979, CauchyDistribution::getPdf($x, $x0, $gamma), '', 0.01);
    }

    public function testCdf()
    {
        $x     = 1;
        $x0    = 0.5;
        $gamma = 2;

        self::assertEquals(0.57798, CauchyDistribution::getCdf($x, $x0, $gamma), '', 0.01);
    }

    public function testEntropy()
    {
        $gamma = 1.5;

        self::assertEquals(log(4 * M_PI * $gamma), CauchyDistribution::getEntropy($gamma), '', 0.01);
    }
}
