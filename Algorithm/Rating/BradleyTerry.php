<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\Rating
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Rating;

/**
 * Calculate rating strength using the Bradley Terry model
 *
 * @package phpOMS\Algorithm\Rating
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @see     https://en.wikipedia.org/wiki/Bradley%E2%80%93Terry_model
 */
final class BradleyTerry
{
    /**
     * Rate the strongest to the weakest team based on historic performances (wins/losses)
     *
     * The following example contains match results (matrix) of teams A-D facing each other (each point is a victory).
     * @example rating(
     *     [
     *          'A' => ['A' => 0, 'B' => 2, 'C' => 0, 'D' => 1],
     *          'B' => ['A' => 3, 'B' => 0, 'C' => 5, 'D' => 0],
     *          'C' => ['A' => 0, 'B' => 3, 'C' => 0, 'D' => 1],
     *          'D' => ['A' => 4, 'B' => 0, 'C' => 3, 'D' => 0],
     *      ],
     *      10
     *  ) // [0.640, 1.043, 0.660, 2.270] -> D is strongest
     *
     * @param array[] $history    Historic results
     * @param int     $iterations Iterations for estimation
     *
     * @return float[] Array of "strength" scores (highest = strongest)
     *
     * @since 1.0.0
     */
    public function rating(array $history, int $iterations = 20) : array
    {
        $keys = \array_keys($history);
        $pOld = [];
        foreach ($keys as $key) {
            $pOld[$key] = 1;
        }

        $p = $pOld;
        for ($i = 0; $i < $iterations; ++$i) {
            foreach ($history as $idx => $row) {
                $W = \array_sum($row);

                $d = 0;
                foreach ($history as $idx2 => $_) {
                    if ($idx === $idx2) {
                        continue;
                    }

                    $d += ($history[$idx][$idx2] + $history[$idx2][$idx])
                        / ($pOld[$idx] + $pOld[$idx2]);
                }

                $p[$idx] = $W / $d;
            }

            $norm = \array_sum($p);
            foreach ($p as $idx => $_) {
                $p[$idx] /= $norm;
            }

            $pOld = $p;
        }

        return $p;
    }
}
