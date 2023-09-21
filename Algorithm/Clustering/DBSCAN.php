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

use phpOMS\Math\Geometry\ConvexHull\MonotoneChain;
use phpOMS\Math\Geometry\Shape\D2\Polygon;
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
 * @todo Expand to n dimensions
 */
final class DBSCAN
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
     * Points outside of any cluster
     *
     * @var PointInterface[]
     * @since 1.0.0
     */
    private array $noisePoints = [];

    /**
     * All points
     *
     * @var PointInterface[]
     * @since 1.0.0
     */
    private array $points = [];

    /**
     * Clusters
     *
     * Array of points assigned to a cluster
     *
     * @var array<int, array>
     * @since 1.0.0
     */
    private array $clusters = [];

    /**
     * Convex hull of all clusters
     *
     * @var array<array>
     * @since 1.0.0
     */
    private array $convexHulls = [];

    /**
     * Cluster points
     *
     * Points in clusters (helper to avoid looping the cluster array)
     *
     * @var array
     * @since 1.0.0
     */
    private array $clusteredPoints = [];

    /**
     * Distance matrix
     *
     * Distances between points
     *
     * @var array<float[]>
     * @since 1.0.0
     */
    private array $distanceMatrix = [];

    /**
     * Constructor
     *
     * @param null|\Closure $metric metric to use for the distance between two points
     *
     * @since 1.0.0
     */
    public function __construct(\Closure $metric = null)
    {
        $this->metric = $metric ?? function (PointInterface $a, PointInterface $b) {
            $aCoordinates = $a->coordinates;
            $bCoordinates = $b->coordinates;

            return MetricsND::euclidean($aCoordinates, $bCoordinates);
        };
    }

    /**
     * Expand cluster with additional point and potential neighbors.
     *
     * @param PointInterface $point     Point to add to a cluster
     * @param array          $neighbors Neighbors of point
     * @param int            $c         Cluster id
     * @param float          $epsilon   Max distance
     * @param int            $minPoints Min amount of points required for a cluster
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function expandCluster(
        PointInterface $point,
        array $neighbors,
        int $c,
        float $epsilon,
        int $minPoints
    ) : void
    {
        $this->clusters[$c][]    = $point;
        $this->clusteredPoints[] = $point;
        $nPoint                  = \reset($neighbors);

        while ($nPoint) {
            $neighbors2 = $this->findNeighbors($nPoint, $epsilon);

            if (\count($neighbors2) >= $minPoints) {
                foreach ($neighbors2 as $nPoint2) {
                    if (!isset($neighbors[$nPoint2->name])) {
                        $neighbors[$nPoint2->name] = $nPoint2;
                    }
                }
            }

            if (!\in_array($nPoint->name, $this->clusteredPoints)) {
                $this->clusters[$c][]    = $nPoint;
                $this->clusteredPoints[] = $nPoint;
            }

            $nPoint = \next($neighbors);
        }
    }

    /**
     * Find neighbors of a point
     *
     * @param PointInterface $point   Base point for potential neighbors
     * @param float          $epsilon Max distance to neighbor
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function findNeighbors(PointInterface $point, float $epsilon) : array
    {
        $neighbors = [];
        foreach ($this->points as $point2) {
            if ($point->isEquals($point2)) {
                $distance = isset($this->distanceMatrix[$point->name])
                    ? $this->distanceMatrix[$point->name][$point2->name]
                    : $this->distanceMatrix[$point2->name][$point->name];

                if ($distance < $epsilon) {
                    $neighbors[$point2->name] = $point2;
                }
            }
        }

        return $neighbors;
    }

    /**
     * Generate distances between points
     *
     * @param array $points Array of all points
     *
     * @return float[]
     *
     * @since 1.0.0
     */
    private function generateDistanceMatrix(array $points) : array
    {
        $distances = [];
        foreach ($points as $point) {
            $distances[$point->name] = [];
            foreach ($points as $point2) {
                $distances[$point->name][$point2->name] = ($this->metric)($point, $point2);
            }
        }

        /** @var float[] $distances */
        return $distances;
    }

    /**
     * Find the cluster for a point
     *
     * @param PointInterface $point Point to find the cluster for
     *
     * @return int Cluster id
     *
     * @since 1.0.0
     */
    public function cluster(PointInterface $point) : int
    {
        if ($this->convexHulls === []) {
            foreach ($this->clusters as $c => $cluster) {
                $points = [];
                foreach ($cluster as $p) {
                    $points[] = $p->coordinates;
                }

                // @todo: this is only good for 2D. Fix this for ND.
                $this->convexHulls[$c] = MonotoneChain::createConvexHull($points);
            }
        }

        foreach ($this->convexHulls as $c => $hull) {
            if (Polygon::isPointInPolygon($point->coordinates, $hull) <= 0) {
                return $c;
            }
        }

        return -1;
    }

    /**
     * Generate the clusters of the points
     *
     * @param PointInterface[] $points    Points to cluster
     * @param float            $epsilon   Max distance
     * @param int              $minPoints Min amount of points required for a cluster
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function generateClusters(array $points, float $epsilon, int $minPoints) : void
    {
        $this->noisePoints     = [];
        $this->clusters        = [];
        $this->clusteredPoints = [];
        $this->points          = $points;
        $this->convexHulls     = [];

        $this->distanceMatrix = $this->generateDistanceMatrix($points);

        $c                  = 0;
        $this->clusters[$c] = [];

        foreach ($this->points as $point) {
            $neighbors = $this->findNeighbors($point, $epsilon);

            if (\count($neighbors) < $minPoints) {
                $this->noisePoints[] = $point->name;
            } elseif (!\in_array($point->name, $this->clusteredPoints)) {
                $this->expandCluster($point, $neighbors, $c, $epsilon, $minPoints);
                ++$c;
                $this->clusters[$c] = [];
            }
        }
    }
}
