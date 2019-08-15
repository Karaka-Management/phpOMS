<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Business\Programming;

use phpOMS\Business\Programming\Metrics;

/**
 * @testdox phpOMS\tests\Business\Programming\MetricsTest: General programming metrics
 *
 * @internal
 */
class MetricsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Test correctness of the ABC calculation
     */
    public function testABCMetric() : void
    {
        self::assertEquals((int) \sqrt(5 * 5 + 11 * 11 + 9 * 9), Metrics::abcScore(5, 11, 9));
    }

    /**
     * @testdox Test correctness of CRAP score
     */
    public function testCRAPMetric() : void
    {
        self::assertEquals(1, Metrics::CRAP(1, 1.0));
        self::assertEquals(10100, Metrics::CRAP(100, 0.0));
    }
}
