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

use phpOMS\Math\Topology\MetricsND;

/**
 * Clustering points
 *
 * @package phpOMS\Algorithm\Clustering
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     ./clustering_overview.png
 * @since   1.0.0
 */
final class Kmeans implements ClusteringInterface
{
    /**
     * Epsilon for float comparison.
     *
     * @var float
     * @since 1.0.0
     */
    public const EPSILON = 4.88e-04;

    /**
     * Metric to calculate the distance between two points
     *
     * @var \Closure
     * @since 1.0.0
     */
    private \Closure $metric;

    /**
     * Points of the cluster centers
     *
     * @var Point[]
     * @since 1.0.0
     */
    private array $clusterCenters = [];

    /**
     * Points of the clusters
     *
     * @var Point[]
     * @since 1.0.0
     */
    private array $clusters = [];

    /**
     * Points
     *
     * @var Point[]
     * @since 1.0.0
     */
    private array $points = [];

    /**
     * Constructor
     *
     * @param null|\Closure $metric metric to use for the distance between two points
     *
     * @since 1.0.0
     */
    public function __construct(?\Closure $metric = null)
    {
        $this->metric = $metric ?? function (Point $a, Point $b) {
            $aCoordinates = $a->coordinates;
            $bCoordinates = $b->coordinates;

            return MetricsND::euclidean($aCoordinates, $bCoordinates);
        };
    }

    /**
     * {@inheritdoc}
     */
    public function cluster(Point $point) : ?Point
    {
        $bestCluster  = null;
        $bestDistance = \PHP_FLOAT_MAX;

        foreach ($this->clusterCenters as $center) {
            if (($distance = ($this->metric)($center, $point)) < $bestDistance) {
                $bestCluster  = $center;
                $bestDistance = $distance;
            }
        }

        return $bestCluster;
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
    public function getNoise() : array
    {
        return [];
    }

    /**
     * Generate the clusters of the points
     *
     * @param Point[] $points   Points to cluster
     * @param int<1, max>      $clusters Amount of clusters
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function generateClusters(array $points, int $clusters) : void
    {
        $this->points   = $points;
        $n              = \count($points);
        $clusterCenters = $this->kpp($points, $clusters);
        $coordinates    = \count($points[0]->coordinates);

        while (true) {
            foreach ($clusterCenters as $center) {
                for ($i = 0; $i < $coordinates; ++$i) {
                    $center->setCoordinate($i, 0);
                }
            }

            foreach ($points as $point) {
                $clusterPoint = $clusterCenters[$point->group];

                ++$clusterPoint->group;
                for ($i = 0; $i < $coordinates; ++$i) {
                    $clusterPoint->setCoordinate($i, $clusterPoint->getCoordinate($i) + $point->getCoordinate($i));
                }
            }

            foreach ($clusterCenters as $center) {
                for ($i = 0; $i < $coordinates; ++$i) {
                    $center->setCoordinate($i, $center->getCoordinate($i) / $center->group);
                }
            }

            $changed = 0;
            foreach ($points as $point) {
                $min = $this->nearestClusterCenter($point, $clusterCenters)[0];

                if ($clusters !== $point->group) {
                    ++$changed;
                    $point->group = $min;
                }
            }

            if ($changed <= $n * self::EPSILON || $n * self::EPSILON < 2) {
                break;
            }
        }

        foreach ($clusterCenters as $key => $center) {
            $center->group = $key;
            $center->name  = (string) $key;
        }

        $this->clusterCenters = $clusterCenters;
    }

    /**
     * Get the index and distance to the nearest cluster center
     *
     * @param Point   $point          Point to get the cluster for
     * @param Point[] $clusterCenters All cluster centers
     *
     * @return array [index, distance]
     *
     * @since 1.0.0
     */
    private function nearestClusterCenter(Point $point, array $clusterCenters) : array
    {
        $index = $point->group;
        $dist  = \PHP_FLOAT_MAX;

        foreach ($clusterCenters as $key => $cPoint) {
            $d = ($this->metric)($cPoint, $point);

            if ($dist > $d) {
                $dist  = $d;
                $index = $key;
            }
        }

        return [$index, $dist];
    }

    /**
     * Initialize cluster centers
     *
     * @param Point[] $points Points to use for the cluster center initialization
     * @param int<0, max>      $n      Amount of clusters to use
     *
     * @return Point[]
     *
     * @since 1.0.0
     */
    private function kpp(array $points, int $n) : array
    {
        $clusters = [clone $points[\array_rand($points, 1)]];

        $d = \array_fill(0, $n, 0.0);

        for ($i = 1; $i < $n; ++$i) {
            $sum = 0;

            foreach ($points as $key => $point) {
                $d[$key] = $this->nearestClusterCenter($point, $clusters)[1];
                $sum += $d[$key];
            }

            $sum *= \mt_rand(0, \mt_getrandmax()) / \mt_getrandmax();

            $found = false;
            foreach ($d as $key => $di) {
                $sum -= $di;

                // The in array check is important to avoid duplicate cluster centers
                if ($sum <= 0 && !\in_array($c = $points[$key], $clusters)) {
                    $clusters[$i] = clone $c;
                    $found        = true;
                }
            }

            while (!$found) {
                if (!\in_array($c = $points[\array_rand($points)], $clusters)) {
                    $clusters[$i] = clone $c;
                    $found        = true;
                }
            }
        }

        foreach ($points as $point) {
            $point->group = $this->nearestClusterCenter($point, $clusters)[0];
        }

        return $clusters;
    }

    /**
     * {@inheritdoc}
     */
    public function getClusters() : array
    {
        if (!empty($this->clusters)) {
            return $this->clusters;
        }

        foreach ($this->points as $point) {
            $c                         = $this->cluster($point);
            $this->clusters[$c?->name] = $point;
        }

        return $this->clusters;
    }
}
