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

namespace phpOMS\tests\Math\Statistic\Forecast;

use phpOMS\Math\Statistic\Forecast\Error;
use phpOMS\Math\Statistic\MeasureOfDispersion;
use phpOMS\Utils\ArrayUtils;

/**
 * @internal
 */
final class ErrorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \phpOMS\Math\Statistic\Forecast\Error
     * @group framework
     */
    public function testForecastError() : void
    {
        self::assertEquals(1000 - 700, Error::getForecastError(1000, 700));
        self::assertEquals(
            [
                400 - 300,
                600 - 700,
                200 - 200,
                500 - -300,
            ],
            Error::getForecastErrorArray(
                [400, 600, 200, 500],
                [300, 700, 200, -300]
            )
        );

        self::assertEquals([Error::getForecastError(1000, 700)], Error::getForecastErrorArray([1000], [700]));
    }

    /**
     * @covers \phpOMS\Math\Statistic\Forecast\Error
     * @group framework
     */
    public function testErrorPercentage() : void
    {
        self::assertEqualsWithDelta(300 / 1000, Error::getPercentageError(300, 1000), 0.01);
        self::assertEquals(
            [
                (400 - 300) / 400,
                (600 - 700) / 600,
                (200 - 200) / 200,
                (500 - -300) / 500,
            ],
            Error::getPercentageErrorArray(
                Error::getForecastErrorArray(
                    [400, 600, 200, 500],
                    [300, 700, 200, -300]
                ),
                [400, 600, 200, 500]
            )
        );
    }

    /**
     * @covers \phpOMS\Math\Statistic\Forecast\Error
     * @group framework
     */
    public function testMeanErrors() : void
    {
        $errors = [
            400 - 300,
            600 - 700,
            200 - 200,
            500 - -300,
        ];

        self::assertEqualsWithDelta(300, Error::getMeanAbsoulteError($errors), 0.01);
        self::assertEqualsWithDelta(125000, Error::getMeanSquaredError($errors), 0.01);
        self::assertEqualsWithDelta(406.2019, Error::getRootMeanSquaredError($errors), 0.01);
    }

    /**
     * @covers \phpOMS\Math\Statistic\Forecast\Error
     * @group framework
     */
    public function testMASE() : void
    {
        $observed = [
            -2.9, -2.83, -0.95, -0.88, 1.21, -1.67, 0.83, -0.27, 1.36,
            -0.34, 0.48, -2.83, -0.95, -0.88, 1.21, -1.67, -2.99, 1.24, 0.64,
        ];

        $forecast = [
            -2.95, -2.7, -1.00, -0.68, 1.50, -1.00, 0.90, -0.37, 1.26,
            -0.54, 0.58, -2.13, -0.75, -0.89, 1.25, -1.65, -3.20, 1.29, 0.60,
        ];

        $errors       = Error::getForecastErrorArray($observed, $forecast);
        $scaledErrors = Error::getScaledErrorArray($errors, $observed);

        self::assertEqualsWithDelta(0.0983, Error::getMeanAbsoluteScaledError($scaledErrors), 0.01);
    }

    /**
     * @covers \phpOMS\Math\Statistic\Forecast\Error
     * @group framework
     */
    public function testMSSE() : void
    {
        $observed = [
            -2.9, -2.83, -0.95, -0.88, 1.21, -1.67, 0.83, -0.27, 1.36,
            -0.34, 0.48, -2.83, -0.95, -0.88, 1.21, -1.67, -2.99, 1.24, 0.64,
        ];

        $forecast = [
            -2.95, -2.7, -1.00, -0.68, 1.50, -1.00, 0.90, -0.37, 1.26,
            -0.54, 0.58, -2.13, -0.75, -0.89, 1.25, -1.65, -3.20, 1.29, 0.60,
        ];

        $errors       = Error::getForecastErrorArray($observed, $forecast);
        $scaledErrors = Error::getScaledErrorArray($errors, $observed);

        self::assertEqualsWithDelta(
            Error::getMeanAbsoluteScaledError(ArrayUtils::power($scaledErrors, 2)),
            Error::getMeanSquaredScaledError($scaledErrors), 0.01
        );
    }

    /**
     * @covers \phpOMS\Math\Statistic\Forecast\Error
     * @group framework
     */
    public function testScaledError() : void
    {
        self::assertEquals(
            [Error::getScaledError(Error::getForecastError(1000, 700), [1000, 800])],
            Error::getScaledErrorArray([Error::getForecastError(1000, 700)], [1000, 800])
        );
    }

    /**
     * @covers \phpOMS\Math\Statistic\Forecast\Error
     * @group framework
     */
    public function testSSE() : void
    {
        $errors = MeasureOfDispersion::meanDeviationArray([99.0, 98.6, 98.5, 101.1, 98.3, 98.6, 97.9, 98.4, 99.2, 99.1]);

        self::assertEqualsWithDelta(6.921, Error::getSumSquaredError($errors), 0.001);
    }

    /**
     * @covers \phpOMS\Math\Statistic\Forecast\Error
     * @group framework
     */
    public function testCoefficientOfDetermination() : void
    {
        self::assertEqualsWithDelta(0.9729, Error::getCoefficientOfDetermination(
            [3, 8, 10, 17, 24, 27],
            [2, 8, 10, 13, 18, 20]
        ), 0.001);

        self::assertEqualsWithDelta(0.922085138, Error::getAdjustedCoefficientOfDetermination(0.944346527, 8, 2), 0.001);
    }

    /**
     * @covers \phpOMS\Math\Statistic\Forecast\Error
     * @group framework
     */
    public function testMAPE() : void
    {
        self::assertEqualsWithDelta(0.17551, Error::getMeanAbsolutePercentageError(
            [112.3, 108.4, 148.9, 117.4],
            [124.7, 103.7, 116.6, 78.5],
        ), 0.001);
    }

    /**
     * @covers \phpOMS\Math\Statistic\Forecast\Error
     * @group framework
     */
    public function testSMAPE() : void
    {
        self::assertEqualsWithDelta(0.049338, Error::getSymmetricMeanAbsolutePercentageError(
            [112.3, 108.4, 148.9, 117.4],
            [124.7, 103.7, 116.6, 78.5],
        ), 0.001);
    }

    /**
     * @covers \phpOMS\Math\Statistic\Forecast\Error
     * @group framework
     */
    public function testMAD() : void
    {
        self::assertEqualsWithDelta(22.075, Error::getMeanAbsoulteDeviation(
            [112.3, 108.4, 148.9, 117.4],
            [124.7, 103.7, 116.6, 78.5],
        ), 0.001);
    }
}
