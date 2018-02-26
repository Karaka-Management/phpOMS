<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution;

class ChiSquaredDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf()
    {
        $df = 15;
        $x = 18.307;
        
        self::assertEquals(0.24687, ChiSquaredDistribution::getPdf($x, $df));
    }
}
