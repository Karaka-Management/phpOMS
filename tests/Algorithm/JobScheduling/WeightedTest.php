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

namespace phpOMS\tests\Algorithm\JobScheduling;

use phpOMS\Algorithm\JobScheduling\Job;
use phpOMS\Algorithm\JobScheduling\Weighted;

/**
 * @testdox phpOMS\tests\Algorithm\JobScheduling\WeightedTest: Job scheduling based on values/profit
 *
 * @internal
 */
final class WeightedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The optimal job combination is selected to maximize the value/profit without overlapping jobs
     * @covers \phpOMS\Algorithm\JobScheduling\Weighted
     * @group framework
     */
    public function testNoOverlappingScheduling() : void
    {
        $jobs = [
            new Job(20, new \DateTime('2003-01-01'), new \DateTime('2010-01-01'), 'A'),
            new Job(50, new \DateTime('2001-01-01'), new \DateTime('2002-01-01'), 'B'),
            new Job(10, new \DateTime('2000-01-01'), null, '0'),
            new Job(100, new \DateTime('2006-01-01'), new \DateTime('2019-01-01'), 'C'),
            new Job(200, new \DateTime('2002-01-01'), new \DateTime('2020-01-01'), 'D'),
            new Job(300, new \DateTime('2004-01-01'), null, '1'),
        ];

        $filtered = WeighteD::solve($jobs);

        $value = 0;
        $names = [];

        foreach ($filtered as $job) {
            $value += $job->getValue();
            $names[] = $job->name;
        }

        self::assertEqualsWithDelta(350, $value, 0.01);

        self::assertTrue(
            \in_array('B', $names)
            && \in_array('1', $names)
        );
    }

    /**
     * @testdox A job list with only one job simply returns one job
     * @covers \phpOMS\Algorithm\JobScheduling\Weighted
     * @group framework
     */
    public function testSmallList() : void
    {
        $jobs = [
            new Job(20, new \DateTime('2003-01-01'), new \DateTime('2010-01-01'), 'A'),
        ];

        $filtered = WeighteD::solve($jobs);

        self::assertEquals($jobs, $filtered);
    }
}
