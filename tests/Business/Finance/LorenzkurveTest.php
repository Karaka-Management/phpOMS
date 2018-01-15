<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Business\Finance;

use phpOMS\Business\Finance\Lorenzkurve;

class LorenzkurveTest extends \PHPUnit\Framework\TestCase
{
    public function testLorenz()
    {
        $arr = [1, 1, 1, 1, 1, 1, 1, 10, 33, 50];

        self::assertTrue(abs(0.71 - LorenzKurve::getGiniCoefficient($arr)) < 0.01);
    }
}

