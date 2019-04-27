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
 declare(strict_types=1);

namespace phpOMS\tests\Math\Statistic\Forecast;

use phpOMS\Math\Statistic\Forecast\Error;

/**
 * @internal
 */
class ErrorTest extends \PHPUnit\Framework\TestCase
{
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

    public function testMeanError() : void
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

    public function testScaledError() : void
    {
        self::assertEquals(
            [Error::getScaledError(Error::getForecastError(1000, 700), [1000, 800])],
            Error::getScaledErrorArray([Error::getForecastError(1000, 700)], [1000, 800])
        );
    }
}
