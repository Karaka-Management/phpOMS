<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Business\Programming;

use phpOMS\Business\Programming\Metrics;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Business\Programming\MetricsTest: General programming metrics')]
final class MetricsTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Test correctness of the ABC calculation')]
    public function testABCMetric() : void
    {
        self::assertEquals((int) \sqrt(5 * 5 + 11 * 11 + 9 * 9), Metrics::abcScore(5, 11, 9));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Test correctness of CRAP score')]
    public function testCRAPMetric() : void
    {
        self::assertEquals(1, Metrics::CRAP(1, 1.0));
        self::assertEquals(10100, Metrics::CRAP(100, 0.0));
    }
}
