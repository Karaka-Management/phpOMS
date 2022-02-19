<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Statistic\Forecast;

use phpOMS\Math\Statistic\Forecast\Forecasts;

/**
 * @internal
 */
final class ForecastsTest extends \PHPUnit\Framework\TestCase
{
	public function testForecastInterval() : void
    {
    	self::assertEqualsWithDelta(
    		[519.3, 543.6],
    		Forecasts::getForecastInteval(531.48, 6.21, 1.96),
    		0.1
    	);
    }
}
