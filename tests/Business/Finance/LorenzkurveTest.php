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

namespace phpOMS\tests\Business\Finance;

use phpOMS\Business\Finance\Lorenzkurve;

/**
 * @testdox phpOMS\tests\Business\Finance\LorenzkurveTest: Lorenz kurve
 *
 * @internal
 */
final class LorenzkurveTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The gini coefficient calculation is correct
     * @group framework
     */
    public function testGiniCoefficient() : void
    {
        $arr = [1, 1, 1, 1, 1, 1, 1, 10, 33, 50];

        self::assertEqualsWithDelta(0.71, LorenzKurve::getGiniCoefficient($arr), 0.01);
    }
}
