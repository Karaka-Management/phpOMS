<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Statistic\Forecast;

use phpOMS\Math\Statistic\Forecast\Error;

class ErrorTest extends \PHPUnit\Framework\TestCase
{
    public function testForecastError()
    {
        self::assertEquals(1000 - 700, Error::getForecastError(1000, 700));
        self::assertEquals(
            [
                400 - 300,
                600 - 700,
                200 - 200,
                500 - -300
            ],
            Error::getForecastErrorArray(
                [400, 600, 200, 500],
                [300, 700, 200, -300]
            )
        );
    }
    
    public function testErrorPercentage()
    {
        self::assertEquals(300 / 1000, Error::getPercentageError(300, 1000), '', 0.01);
        self::assertEquals(
            [
                (400 - 300) / 400,
                (600 - 700) / 600,
                (200 - 200) / 200,
                (500 - -300) / 500
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
    
    public function testMeanError()
    {
        $errors = [
            400 - 300,
            600 - 700,
            200 - 200,
            500 - -300
        ];
        
        self::assertEquals(300, Error::getMeanAbsoulteError($errors), '', 0.01);
        self::assertEquals(125000, Error::getMeanSquaredError($errors), '', 0.01);
        self::assertEquals(406.2019, Error::getRootMeanSquaredError($errors), '', 0.01);
    }
}
