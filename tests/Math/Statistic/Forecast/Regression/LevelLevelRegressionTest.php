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

use phpOMS\Math\Statistic\Forecast\Regression\LevelLevelRegression;

class LevelLevelRegressionTest extends \PHPUnit\Framework\TestCase
{
    protected $reg = null;

    protected function setUp() : void
    {
        // y = 3 + 4 * x
        $x = [0, 1, 2, 3, 4];
        $y = [3, 7, 11, 15, 19];

        $this->reg = LevelLevelRegression::getRegression($x, $y);
    }

    public function testRegression() : void
    {
        self::assertEquals(['b0' => 3, 'b1' => 4], $this->reg, '', 0.2);
    }

    public function testSlope() : void
    {
        self::assertEquals(4, LevelLevelRegression::getSlope($this->reg['b1'], 0, 0));
    }

    public function testElasticity() : void
    {
        self::assertEquals(0.7273, LevelLevelRegression::getElasticity($this->reg['b1'], 11, 2), '', 0.01);
    }

    /**
     * @expectedException \phpOMS\Math\Matrix\Exception\InvalidDimensionException
     */
    public function testInvalidDimension() : void
    {
        $x = [1,2, 3];
        $y = [1,2, 3, 4];

        LevelLevelRegression::getRegression($x, $y);
    }
}
