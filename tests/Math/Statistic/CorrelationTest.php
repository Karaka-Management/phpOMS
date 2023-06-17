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

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\Correlation;

/**
 * @testdox phpOMS\tests\Math\Statistic\CorrelationTest: Correlations
 *
 * @internal
 */
final class CorrelationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The correlation coefficient (Bravis Person) is calculated correctly
     * @group framework
     */
    public function testBravisPersonCorrelationCoefficientPopulation() : void
    {
        self::assertEqualsWithDelta(
            0.8854,
            Correlation::bravaisPersonCorrelationCoefficientPopulation(
                [1, 2, 3, 4, 5, 6, 7],
                [3, 4, 5, 9, 7, 8, 9]
            ), 0.01
        );
    }

    /**
     * @testdox The correlation coefficient (Bravis Person) is calculated correctly on a sample
     * @group framework
     */
    public function testBravisPersonCorrelationCoefficientSample() : void
    {
        self::assertEqualsWithDelta(
            0.8854,
            Correlation::bravaisPersonCorrelationCoefficientSample(
                [1, 2, 3, 4, 5, 6, 7],
                [3, 4, 5, 9, 7, 8, 9]
            ), 0.01
        );
    }

    /**
     * @testdox The autocorrelation coefficient is calculated correctly
     * @group framework
     */
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

    /**
     * @testdox The portmanteau test (Box Pierce) is correct
     * @group framework
     */
    public function testPortmanteauTestBoxPierce() : void
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
    }

    /**
     * @testdox The portmanteau test (Ljung Box) is correct
     * @group framework
     */
    public function testPortmanteauTestLjungBox() : void
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

        self::assertEqualsWithDelta(24.92, Correlation::ljungBoxTest($correlations, 24, 48), 0.01);
    }
}
