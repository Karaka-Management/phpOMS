<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Statistic\Forecast\Regression\PolynomialRegression;

/**
 * @testdox phpOMS\tests\Math\Statistic\Forecast\Regression\PolynomialRegressionTest: Polynomial regression
 *
 * @internal
 */
class PolynomialRegressionTest extends \PHPUnit\Framework\TestCase
{
    protected $reg = null;

    protected function setUp() : void
    {
        // y = 1.0 + 2.0 * x + 3.0 * x^2
        $x = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $y = [1, 6, 17, 34, 57, 86, 121, 162, 209, 262, 321];

        $this->reg = PolynomialRegression::getRegression($x, $y);
    }

    /**
     * @testdox The regression parameters are calculated correctly
     * @group framework
     */
    public function testRegression() : void
    {
        self::assertEqualsWithDelta(['a' => 1, 'b' => 2, 'c' => 3], $this->reg, 0.2);
    }

    /**
     * @testdox Different dimension sizes for x and y coordinates throw a InvalidDimensionException
     * @group framework
     */
    public function testInvalidDimension() : void
    {
        self::expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $x = [1,2, 3];
        $y = [1,2, 3, 4];

        PolynomialRegression::getRegression($x, $y);
    }
}
