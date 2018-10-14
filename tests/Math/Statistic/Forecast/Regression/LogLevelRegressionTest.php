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
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Statistic\Forecast\Regression\LogLevelRegression;

class LogLevelRegressionTest extends \PHPUnit\Framework\TestCase
{
    protected $reg = null;

    protected function setUp()
    {
        // ln(y) = -1 + 2 * x => y = e^(-1 + 2 * x)
        $x = [0.25, 0.5, 1, 1.5];
        $y = [0.6065, 1, 2.718, 7.389];

        $this->reg = LogLevelRegression::getRegression($x, $y);
    }

    public function testRegression()
    {
        self::assertEquals(['b0' => -1, 'b1' => 2], $this->reg, '', 0.2);
    }

    public function testSlope()
    {
        $y = 3;
        self::assertEquals($this->reg['b1'] * $y, LogLevelRegression::getSlope($this->reg['b1'], $y, 0), '', 0.2);
    }

    public function testElasticity()
    {
        $x = 2;
        self::assertEquals($this->reg['b1'] * $x, LogLevelRegression::getElasticity($this->reg['b1'], 0, $x), '', 0.2);
    }

    /**
     * @expectedException \phpOMS\Math\Matrix\Exception\InvalidDimensionException
     */
    public function testInvalidDimension()
    {
        $x = [1,2, 3];
        $y = [1,2, 3, 4];

        LogLevelRegression::getRegression($x, $y);
    }
}
