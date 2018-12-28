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

namespace phpOMS\tests\Business\Marketing;

use phpOMS\Business\Marketing\Metrics;

class MetricsTest extends \PHPUnit\Framework\TestCase
{
    public function testMetrics() : void
    {
        self::assertTrue(0.85 - Metrics::getCustomerRetention(105, 20, 100) < 0.01);
    }
}
