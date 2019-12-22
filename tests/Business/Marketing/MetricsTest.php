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

namespace phpOMS\tests\Business\Marketing;

use phpOMS\Business\Marketing\Metrics;

/**
 * @testdox phpOMS\tests\Business\Marketing\MetricsTest: General marketing metrics
 *
 * @internal
 */
class MetricsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Test the correctness of the customer retention calculation
     * @group framework
     */
    public function testCustomerRetention() : void
    {
        self::assertEqualsWithDelta(0.85, Metrics::getCustomerRetention(105, 20, 100), 0.01);
    }
}
