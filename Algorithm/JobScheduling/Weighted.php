<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\JobScheduling
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\JobScheduling;

/**
 * Job scheduling algorithm with no overlapping jobs
 *
 * @package phpOMS\Algorithm\JobScheduling
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Weighted
{
    /**
     * Constructor
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Sort jobs by end date.
     *
     * @param JobInterface $j1 Job 1
     * @param JobInterface $j2 Job 2
     *
     * @return int
     *
     * @todo Orange-Management/phpOMS#243
     *  [JobScheduling] Implement sortByEnd test coverage
     *  All 3 if cases are not covered. Implement the tests!
     *
     * @since 1.0.0
     */
    private static function sortByEnd(JobInterface $j1, JobInterface $j2) : int
    {
        if ($j1->getEnd() === null && $j2->getEnd() !== null) {
            return 1;
        }

        if ($j1->getEnd() === null && $j2->getEnd() === null) {
            return 0;
        }

        if ($j1->getEnd() !== null && $j2->getEnd() === null) {
            return -1;
        }

        return $j1->getEnd()->getTimestamp() <=> $j2->getEnd()->getTimestamp();
    }

    /**
     * Search for a none-conflicting job that comes befor a defined job
     *
     * @param JobInterface[] $jobs  List of jobs
     * @param int            $pivot Job to find the previous job to
     *
     * @return int
     *
     * @since 1.0.0
     */
    private static function binarySearch(array $jobs, int $pivot) : int
    {
        $lo = 0;
        $hi = $pivot - 1;

        while ($lo <= $hi) {
            $mid = (int) (($lo + $hi) / 2);

            if ($jobs[$mid]->getEnd() !== null
                && $jobs[$mid]->getEnd()->getTimestamp() <= $jobs[$pivot]->getStart()->getTimestamp()
            ) {
                if ($jobs[$mid + 1]->getEnd() !== null
                    && $jobs[$mid + 1]->getEnd()->getTimestamp() <= $jobs[$pivot]->getStart()->getTimestamp()
                ) {
                    $lo = $mid + 1;
                } else {
                    return $mid;
                }
            } else {
                $hi = $mid - 1;
            }
        }

        return -1;
    }

    /**
     * Maximize the value of the job execution without overlapping jobs
     *
     * @param JobInterface[] $jobs Jobs to filter
     *
     * @return JobInterface[]
     *
     * @todo Orange-Management/phpOMS#244
     *  [JobScheduling] Implement test for Jobs with same value.
     *  There is no test case for the else clause in the `solve` function. Implement it.
     *
     * @since 1.0.0
     */
    public static function solve(array $jobs) : array
    {
        $n = \count($jobs);

        if ($n < 2) {
            return $jobs;
        }

        \usort($jobs, [self::class, 'sortByEnd']);

        $valueTable = [$jobs[0]->getValue()];

        $resultTable    = [];
        $resultTable[0] = [$jobs[0]];

        for ($i = 1; $i < $n; ++$i) {
            $value = $jobs[$i]->getValue();
            $jList = [$jobs[$i]];
            $l     = self::binarySearch($jobs, $i);

            if ($l != -1) {
                $value += $valueTable[$l];
                $jList  = \array_merge($resultTable[$l], $jList);
            }

            if ($value > $valueTable[$i - 1]) {
                $valueTable[$i]  = $value;
                $resultTable[$i] = $jList;
            } else {
                $valueTable[$i]  = $valueTable[$i - 1];
                $resultTable[$i] = $resultTable[$i - 1];
            }
        }

        return $resultTable[$n - 1];
    }
}
