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

use phpOMS\Math\Statistic\Forecast\Regression\LevelLevelRegression;
use phpOMS\Math\Statistic\Forecast\Error;
use phpOMS\Math\Stochastic\Distribution\TDistribution;

/**
 * @testdox phpOMS\tests\Math\Statistic\Forecast\Regression\LevelLevelRegressionTest: Level level regression
 *
 * @internal
 */
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

    /**
     * @testdox The regression parameters are calcualated correctly
     */
    public function testRegression() : void
    {
        self::assertEqualsWithDelta(['b0' => 3, 'b1' => 4], $this->reg, 0.2);
    }

    /**
     * @testdox The slope is calculated correctly
     */
    public function testSlope() : void
    {
        self::assertEquals(4, LevelLevelRegression::getSlope($this->reg['b1'], 0, 0));
    }

    /**
     * @testdox The elasticity is calculated correctly
     */
    public function testElasticity() : void
    {
        self::assertEqualsWithDelta(0.7273, LevelLevelRegression::getElasticity($this->reg['b1'], 11, 2), 0.01);
    }

    /**
     * @testdox The standard error of the population is calculated correctly
     */
    public function testStandardErrorOfRegressionPopulation() : void
    {
        $x   = [1, 2, 3, 4, 5];
        $y   = [1, 2, 1.3, 3.75, 2.25];
        $reg = LevelLevelRegression::getRegression($x, $y);

        $forecast = [];
        foreach ($x as $value) {
            $forecast[] = $reg['b0'] + $reg['b1'] * $value;
        }

        $errors = Error::getForecastErrorArray($y, $forecast);
        self::assertEqualsWithDelta(0.747, LevelLevelRegression::getStandardErrorOfRegressionPopulation($errors), 0.001);
    }

    /**
     * @testdox The standard error of the sample is calculated correctly
     */
    public function testStandardErrorOfRegressionSample() : void
    {
        $x   = [1, 2, 3, 4, 5];
        $y   = [1, 2, 1.3, 3.75, 2.25];
        $reg = LevelLevelRegression::getRegression($x, $y);

        $forecast = [];
        foreach ($x as $value) {
            $forecast[] = $reg['b0'] + $reg['b1'] * $value;
        }

        $errors = Error::getForecastErrorArray($y, $forecast);
        self::assertEqualsWithDelta(0.964, LevelLevelRegression::getStandardErrorOfRegressionSample($errors), 0.001);
    }

    /**
     * @testdox The prediction interval is calculated correctly
     */
    public function testPredictionInterval() : void
    {
        $x   = [1, 2, 3, 4, 5];
        $y   = [1, 2, 1.3, 3.75, 2.25];
        $reg = LevelLevelRegression::getRegression($x, $y);

        $forecast = [];
        foreach ($x as $value) {
            $forecast[] = $reg['b0'] + $reg['b1'] * $value;
        }

        $errors = Error::getForecastErrorArray($y, $forecast);
        $mse    = Error::getMeanSquaredError($errors, 2);
        self::assertEqualsWithDelta(
            [-1.1124355, 7.7824355],
            LevelLevelRegression::getPredictionIntervalMSE(6, $reg['b0'] + $reg['b1'] * 6, $x, $mse, TDistribution::TABLE[3]['0.95']),
            0.001
        );
    }

    /**
     * @testdox Different dimension sizes for x and y coordinates throw a InvalidDimensionException
     */
    public function testInvalidDimension() : void
    {
        self::expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $x = [1,2, 3];
        $y = [1,2, 3, 4];

        LevelLevelRegression::getRegression($x, $y);
    }
}
