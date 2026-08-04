<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Statistic\Forecast\Regression\LogLogRegression;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Statistic\Forecast\Regression\LogLogRegressionTest: Log log regression')]
final class LogLogRegressionTest extends \PHPUnit\Framework\TestCase
{
    protected $reg = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        // ln(y) = 2 + 3 * ln(x) => y = e^(2 + 3 * ln(x))
        $x = [0.25, 0.5, 1, 1.5];
        $y = [0.115, 0.924, 7.389, 24.938];

        $this->reg = LogLogRegression::getRegression($x, $y);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The regression parameters are calculated correctly')]
    public function testRegression() : void
    {
        self::assertEqualsWithDelta(['b0' => 2, 'b1' => 3], $this->reg, 0.2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The slope is calculated correctly')]
    public function testSlope() : void
    {
        $y = 3;
        $x = 2;
        self::assertEqualsWithDelta($this->reg['b1'] * $y / $x, LogLogRegression::getSlope($this->reg['b1'], $y, $x), 0.2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The elasticity is calculated correctly')]
    public function testElasticity() : void
    {
        self::assertEqualsWithDelta($this->reg['b1'], LogLogRegression::getElasticity($this->reg['b1'], 0, 0), 0.2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dimension sizes for x and y coordinates throw a InvalidDimensionException')]
    public function testInvalidDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $x = [1,2, 3];
        $y = [1,2, 3, 4];

        LogLogRegression::getRegression($x, $y);
    }
}
