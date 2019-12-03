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

use phpOMS\Math\Statistic\Forecast\Regression\LevelLogRegression;

/**
 * @testdox phpOMS\tests\Math\Statistic\Forecast\Regression\LevelLogRegressionTest: Level log regression
 *
 * @internal
 */
class LevelLogRegressionTest extends \PHPUnit\Framework\TestCase
{
    protected $reg = null;

    protected function setUp() : void
    {
        // y = 1 + log(x)
        $x = [0.25, 0.5, 1, 1.5];
        $y = [-0.386, 0.307, 1, 1.405];

        $this->reg = LevelLogRegression::getRegression($x, $y);
    }

    /**
     * @testdox The regression parameters are calcualated correctly
     * @group framework
     */
    public function testRegression() : void
    {
        self::assertEqualsWithDelta(['b0' => 1, 'b1' => 1], $this->reg, 0.2);
    }

    /**
     * @testdox The slope is calculated correctly
     * @group framework
     */
    public function testSlope() : void
    {
        $x = 2;
        self::assertEqualsWithDelta($this->reg['b1'] / $x, LevelLogRegression::getSlope($this->reg['b1'], 0, $x), 0.2);
    }

    /**
     * @testdox The elasticity is calculated correctly
     * @group framework
     */
    public function testElasticity() : void
    {
        $y = 3;
        self::assertEqualsWithDelta($this->reg['b1'] / $y, LevelLogRegression::getElasticity($this->reg['b1'], $y, 0), 0.2);
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

        LevelLogRegression::getRegression($x, $y);
    }
}
