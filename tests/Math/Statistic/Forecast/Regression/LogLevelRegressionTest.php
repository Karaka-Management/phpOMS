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

namespace phpOMS\tests\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Statistic\Forecast\Regression\LogLevelRegression;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Statistic\Forecast\Regression\LogLevelRegressionTest: Log level regression')]
final class LogLevelRegressionTest extends \PHPUnit\Framework\TestCase
{
    protected $reg = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        // ln(y) = -1 + 2 * x => y = e^(-1 + 2 * x)
        $x = [0.25, 0.5, 1, 1.5];
        $y = [0.6065, 1, 2.718, 7.389];

        $this->reg = LogLevelRegression::getRegression($x, $y);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The regression parameters are calculated correctly')]
    public function testRegression() : void
    {
        self::assertEqualsWithDelta(['b0' => -1, 'b1' => 2], $this->reg, 0.2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The slope is calculated correctly')]
    public function testSlope() : void
    {
        $y = 3;
        self::assertEqualsWithDelta($this->reg['b1'] * $y, LogLevelRegression::getSlope($this->reg['b1'], $y, 0), 0.2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The elasticity is calculated correctly')]
    public function testElasticity() : void
    {
        $x = 2;
        self::assertEqualsWithDelta($this->reg['b1'] * $x, LogLevelRegression::getElasticity($this->reg['b1'], 0, $x), 0.2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for x and y coordinates throw a InvalidDimensionException')]
    public function testInvalidDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $x = [1,2, 3];
        $y = [1,2, 3, 4];

        LogLevelRegression::getRegression($x, $y);
    }
}
