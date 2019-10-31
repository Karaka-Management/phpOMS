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

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\WeibullDistribution;

/**
 * @internal
 */
class WeibullDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf() : void
    {
        self::assertEqualsWithDelta(0.213668559, WeibullDistribution::getPdf(3, 4, 2), 0.001);
        self::assertEqualsWithDelta(0.0, WeibullDistribution::getPdf(-1, 4, 2), 0.001);
    }

    public function testCdf() : void
    {
        self::assertEqualsWithDelta(0.430217175, WeibullDistribution::getCdf(3, 4, 2), 0.001);
        self::assertEqualsWithDelta(0.0, WeibullDistribution::getCdf(-1, 4, 2), 0.001);
    }
}
