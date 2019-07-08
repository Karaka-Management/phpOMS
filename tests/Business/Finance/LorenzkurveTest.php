<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Business\Finance;

use phpOMS\Business\Finance\Lorenzkurve;

/**
 * @internal
 */
class LorenzkurveTest extends \PHPUnit\Framework\TestCase
{
    public function testLorenz() : void
    {
        $arr = [1, 1, 1, 1, 1, 1, 1, 10, 33, 50];

        self::assertTrue(\abs(0.71 - LorenzKurve::getGiniCoefficient($arr)) < 0.01);
    }
}
