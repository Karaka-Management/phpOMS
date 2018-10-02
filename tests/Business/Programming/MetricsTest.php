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

namespace phpOMS\tests\Business\Programming;

use phpOMS\Business\Programming\Metrics;

class MetricsTest extends \PHPUnit\Framework\TestCase
{
    public function testMetrics()
    {
        self::assertEquals((int) \sqrt(5 * 5 + 11 * 11 + 9 * 9), Metrics::abcScore(5, 11, 9));

        self::assertEquals(1, Metrics::CRAP(1, 1.0));
        self::assertEquals(10100, Metrics::CRAP(100, 0.0));
    }
}
