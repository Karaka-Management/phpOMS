<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\Clustering
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Clustering;

use phpOMS\Math\Topology\KernelsND;
use phpOMS\Math\Topology\MetricsND;

/**
 * Clustering points
 *
 * @package phpOMS\Algorithm\Clustering
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @see     ./clustering_overview.png
 */
final class MeanShift
{
    private \Closure $kernel;

    private \Closure $metric;

    private array $points;

    /**
     * Points outside of any cluster
     *
     * @var PointInterface[]
     * @since 1.0.0
     */
    private array $noisePoints = [];

    /**
     * Cluster points
     *
     * Points in clusters (helper to avoid looping the cluster array)
     *
     * @var array
     * @since 1.0.0
     */
    private array $clusters = [];

    /**
     * Points of the cluster centers
     *
     * @var PointInterface[]
     * @since 1.0.0
     */
    private $clusterCenters = [];

    public const MIN_DISTANCE = 0.000001;
    public const GROUP_DISTANCE_TOLERANCE = .1;

    /**
     * Constructor
     *
     * @param null|\Closure    $metric   metric to use for the distance between two points
     *
     * @since 1.0.0
     */
    public function __construct(\Closure $metric = null, \Closure $kernel = null)
    {
        $this->metric = $metric ?? function (PointInterface $a, PointInterface $b) {
            $aCoordinates = $a->coordinates;
            $bCoordinates = $b->coordinates;

            return MetricsND::euclidean($aCoordinates, $bCoordinates);
        };

        $this->kernel = $kernel ?? function (array $distances, array $bandwidths) {
            return KernelsND::gaussianKernel($distances, $bandwidths);
        };
    }

    public function generateClusters(array $points, array $bandwidth) : void
    {
        $shiftPoints = $points;
        $maxMinDist = 1;

        $stillShifting = \array_fill(0, \count($points), true);

        $pointLength = \count($shiftPoints);

        while ($maxMinDist > self::MIN_DISTANCE) {
            $maxMinDist = 0;

            for ($i = 0; $i < $pointLength; ++$i) {
                if (!$stillShifting[$i]) {
                    continue;
                }

                $pNew = $shiftPoints[$i];
                $pNewStart = $pNew;
                $pNew = $this->shiftPoint($pNew, $points, $bandwidth);
                $dist = ($this->metric)($pNew, $pNewStart);

                if ($dist > $maxMinDist) {
                    $maxMinDist = $dist;
                }

                if ($dist < self::MIN_DISTANCE) {
                    $stillShifting[$i] = false;
                }

                $shiftPoints[$i] = $pNew;
            }
        }

        // @todo create an array of noisePoints like in the DBSCAN. That array can be empty or not depending on the bandwidth defined

        $this->clusters = $this->groupPoints($shiftPoints);
        $this->clusterCenters = $shiftPoints;
    }

    private function shiftPoint(PointInterface $point, array $points, array $bandwidth) : PointInterface
    {
        $scaleFactor = 0.0;

        $shifted = clone $point;

        foreach ($points as $pTemp) {
            $dist = ($this->metric)($point, $pTemp);
            $weight = ($this->kernel)($dist, $bandwidth);

            foreach ($point->coordinates as $idx => $_) {
                if (!isset($shifted->coordinates[$idx])) {
                    $shifted->coordinates[$idx] = 0;
                }

                $shifted->coordinates[$idx] += $pTemp->coordinates[$idx] * $weight;
            }

            $scaleFactor += $weight;
        }

        foreach ($shifted->coordinates as $idx => $_) {
            $shifted->coordinates[$idx] /= $scaleFactor;
        }

        return $shifted;
    }

    private function groupPoints(array $points) : array
    {
        $groupAssignment = [];
        $groups = [];
        $groupIndex = 0;

        foreach ($points as $point) {
            $nearestGroupIndex = $this->findNearestGroup($point, $groups);

            if ($nearestGroupIndex === -1) {
                // create new group
                $groups[] = [$point];
                $groupAssignment[] = $groupIndex;
                ++$groupIndex;
            } else {
                $groupAssignment[] = $nearestGroupIndex;
                $groups[$nearestGroupIndex][] = $point;
            }
        }

        return $groupAssignment;
    }

    private function findNearestGroup(PointInterface $point, array $groups) : int
    {
        $nearestGroupIndex = -1;
        $index = 0;

        foreach ($groups as $group) {
            $distanceToGroup = $this->distanceToGroup($point, $group);

            if ($distanceToGroup < self::GROUP_DISTANCE_TOLERANCE) {
                $nearestGroupIndex = $index;
                break;
            }

            ++$index;
        }

        return $nearestGroupIndex;
    }

    private function distanceToGroup(PointInterface $point, array $group) : float
    {
        $minDistance = \PHP_FLOAT_MAX;

        foreach ($group as $pt) {
            $dist = ($this->metric)($point, $pt);

            if ($dist < $minDistance) {
                $minDistance = $dist;
            }
        }

        return $minDistance;
    }

    /**
     * Find the cluster for a point
     *
     * @param PointInterface $point Point to find the cluster for
     *
     * @return null|PointInterface Cluster center point
     *
     * @since 1.0.0
     */
    public function cluster(PointInterface $point) : ?PointInterface
    {
        $clusterId = $this->findNearestGroup($point, $this->clusters);

        return $this->clusterCenters[$clusterId] ?? null;
    }
}
