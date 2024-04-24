<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * @see     ./clustering_overview.png
 * @since   1.0.0
 *
 * @todo Implement noise points
 */
final class MeanShift implements ClusteringInterface
{
    /**
     * Min distance for clustering
     *
     * As long as a point is further away as the min distance the shifting is performed
     *
     * @var float
     * @since 1.0.0
     */
    public const MIN_DISTANCE = 0.001;

    /**
     * Kernel function
     *
     * @var \Closure
     * @since 1.0.0
     */
    private \Closure $kernel;

    /**
     * Metric function
     *
     * @var \Closure
     * @since 1.0.0
     */
    private \Closure $metric;

    private array $points;

    /**
     * Points outside of any cluster
     *
     * @var Point[]
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
     * @var Point[]
     * @since 1.0.0
     */
    private array $clusterCenters = [];

    /**
     * Max distance to cluster to be still considered part of cluster
     *
     * @var float
     * @since 1.0.0
     */
    public float $groupDistanceTolerance = 0.1;

    /**
     * Constructor
     *
     * Both the metric and kernel function need to be of the same dimension.
     *
     * @param null|\Closure $metric Metric to use for the distance between two points
     * @param null|\Closure $kernel Kernel
     *
     * @since 1.0.0
     */
    public function __construct(?\Closure $metric = null, ?\Closure $kernel = null)
    {
        $this->metric = $metric ?? function (Point $a, Point $b) {
            $aCoordinates = $a->coordinates;
            $bCoordinates = $b->coordinates;

            return MetricsND::euclidean($aCoordinates, $bCoordinates);
        };

        $this->kernel = $kernel ?? function (array $distances, array $bandwidths) {
            return KernelsND::gaussianKernel($distances, $bandwidths);
        };
    }

    /**
     * Generate the clusters of the points
     *
     * @param Point[] $points    Points to cluster
     * @param array<int|float> $bandwidth Bandwidth(s)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function generateClusters(array $points, array $bandwidth) : void
    {
        $this->points = $points;
        $shiftPoints  = $points;
        $maxMinDist   = 1;

        $stillShifting = \array_fill(0, \count($points), true);

        $pointLength = \count($shiftPoints);

        while ($maxMinDist > self::MIN_DISTANCE) {
            $maxMinDist = 0;

            for ($i = 0; $i < $pointLength; ++$i) {
                if (!$stillShifting[$i]) {
                    continue;
                }

                $pNew      = $shiftPoints[$i];
                $pNewStart = $pNew;
                $pNew      = $this->shiftPoint($pNew, $points, $bandwidth);
                $dist      = ($this->metric)($pNew, $pNewStart);

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

        $this->clusters       = $this->groupPoints($shiftPoints);
        $this->clusterCenters = $shiftPoints;
    }

    /**
     * Perform shift on a point
     *
     * @param Point   $point     Point to shift
     * @param Point   $points    Array of all points
     * @param array<int|float> $bandwidth Bandwidth(s)
     *
     * @return Point
     *
     * @since 1.0.0
     */
    private function shiftPoint(Point $point, array $points, array $bandwidth) : Point
    {
        $scaleFactor = 0.0;

        $shifted = clone $point;

        foreach ($points as $pTemp) {
            $dist   = ($this->metric)($point, $pTemp);
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

    /**
     * Group points together into clusters
     *
     * @param Point[] $points Array of points to assign to groups
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function groupPoints(array $points) : array
    {
        $groupAssignment = [];
        $groups          = [];
        $groupIndex      = 0;

        foreach ($points as $point) {
            $nearestGroupIndex = $this->findNearestGroup($point, $groups);

            if ($nearestGroupIndex === -1) {
                // create new group
                $groups[]          = [$point];
                $groupAssignment[] = $groupIndex;

                ++$groupIndex;
            } else {
                $groupAssignment[]            = $nearestGroupIndex;
                $groups[$nearestGroupIndex][] = $point;
            }
        }

        return $groupAssignment;
    }

    /**
     * Find the closest cluster/group of a point
     *
     * @param Point          $point  Point to find the cluster for
     * @param array<Point[]> $groups Clusters
     *
     * @return int
     *
     * @since 1.0.0
     */
    private function findNearestGroup(Point $point, array $groups) : int
    {
        $nearestGroupIndex = -1;
        $index             = 0;

        foreach ($groups as $group) {
            $distanceToGroup = $this->distanceToGroup($point, $group);

            if ($distanceToGroup < $this->groupDistanceTolerance) {
                $nearestGroupIndex = $index;

                break;
            }

            ++$index;
        }

        return $nearestGroupIndex;
    }

    /**
     * Find distance of point to best cluster/group
     *
     * @param Point   $point Point to find the cluster for
     * @param Point[] $group Clusters
     *
     * @return float Distance
     *
     * @since 1.0.0
     */
    private function distanceToGroup(Point $point, array $group) : float
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
     * {@inheritdoc}
     */
    public function getCentroids() : array
    {
        return $this->clusterCenters;
    }

    /**
     * {@inheritdoc}
     */
    public function cluster(Point $point) : ?Point
    {
        $clusterId = $this->findNearestGroup($point, $this->clusters);

        return $this->clusterCenters[$clusterId] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getNoise() : array
    {
        return $this->noisePoints;
    }

    /**
     * {@inheritdoc}
     */
    public function getClusters() : array
    {
        return $this->clusters;
    }
}
