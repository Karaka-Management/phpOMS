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

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\Correlation;

/**
 * @internal
 */
class CorrelationTest extends \PHPUnit\Framework\TestCase
{
    public function testBravisPersonCorrelationCoefficient() : void
    {
        self::assertEqualsWithDelta(
            0.8854,
            Correlation::bravaisPersonCorrelationCoefficient(
                [1, 2, 3, 4, 5, 6, 7],
                [3, 4, 5, 9, 7, 8, 9]
            ), 0.01
        );
    }

    public function testAutocorrelationCoefficient() : void
    {
        $data = [
            1, 20, 31, 8, 40, 41, 46, 89, 72, 45, 81, 93,
            41, 63, 17, 96, 68, 27, 41, 17, 26, 75, 63, 93,
            18, 93, 80, 36, 4, 23, 81, 47, 61, 27, 13, 25,
            51, 20, 65, 45, 87, 68, 36, 31, 79, 7, 95, 37,
        ];

        self::assertEqualsWithDelta(0.022, Correlation::autocorrelationCoefficient($data, 1), 0.01);
        self::assertEqualsWithDelta(0.098, Correlation::autocorrelationCoefficient($data, 2), 0.01);
    }

    public function testPortmanteauTest() : void
    {
        $data = [
            1, 20, 31, 8, 40, 41, 46, 89, 72, 45, 81, 93,
            41, 63, 17, 96, 68, 27, 41, 17, 26, 75, 63, 93,
            18, 93, 80, 36, 4, 23, 81, 47, 61, 27, 13, 25,
            51, 20, 65, 45, 87, 68, 36, 31, 79, 7, 95, 37,
        ];

        $correlations = [];
        for ($i = 0; $i < 24; ++$i) {
            $correlations[] = Correlation::autocorrelationCoefficient($data, $i + 1);
        }

        self::assertEqualsWithDelta(16.46, Correlation::boxPierceTest($correlations, 24, 48), 0.01);
        self::assertEqualsWithDelta(24.92, Correlation::ljungBoxTest($correlations, 24, 48), 0.01);
    }
}
