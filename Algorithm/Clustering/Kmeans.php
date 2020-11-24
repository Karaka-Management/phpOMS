<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\Clustering
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Clustering;

/**
 * Clustering points
 *
 * @package phpOMS\Algorithm\Clustering
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Kmeans
{
    /**
     * Metric to calculate the distance between two points
     *
     * @var \Closure
     * @since 1.0.0
     */
    private \Closure $metric;

    /**
     * Amount of different clusters
     *
     * @var int
     * @since 1.0.0
     */
    private int $clusters = 1;

    /**
     * Points of the cluster centers
     *
     * @var PointInterface[]
     * @since 1.0.0
     */
    private $clusterCenters = [];

    /**
     * Points to clusterize
     *
     * @var PointInterface[]
     * @since 1.0.0
     */
    private array $points = [];

    /**
     * Constructor
     *
     * @param PointInterface[] $points   Points to cluster
     * @param int              $clusters Amount of clusters
     * @param null|\Closure    $metric   metric to use for the distance between two points
     *
     * @since 1.0.0
     */
    public function __construct(array $points, int $clusters, \Closure $metric = null)
    {
        $this->points   = $points;
        $this->clusters = $clusters;
        $this->metric   = $metric ?? function (PointInterface $a, PointInterface $b) {
            $aCoordinates = $a->getCoordinates();
            $bCoordinates = $b->getCoordinates();

            $n   = \count($aCoordinates);
            $sum = 0;

            for ($i = 0; $i < $n; ++$i) {
                $sum = ($aCoordinates[$i] - $bCoordinates[$i]) * ($aCoordinates[$i] - $bCoordinates[$i]);
            }

            return $sum;
        };

        $this->generateClusters($points, $clusters);
    }

    /**
     * Find the cluster for a point
     *
     * @param PointInterface $point Point to find the cluster for
     *
     * @return null|PointInterface
     *
     * @since 1.0.0
     */
    public function cluster(PointInterface $point) : ?PointInterface
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
     * Generate the clusters of the points
     *
     * @param PointInterface[] $points   Points to cluster
     * @param int              $clusters Amount of clusters
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function generateClusters(array $points, int $clusters) : void
    {
        $n              = \count($points);
        $clusterCenters = $this->kpp($points, $clusters);
        $coordinates    = \count($points[0]->getCoordinates());

        while (true) {
            foreach ($clusterCenters as $center) {
                for ($i = 0; $i < $coordinates; ++$i) {
                    $center->setCoordinate($i, 0);
                }
            }

            foreach ($points as $point) {
                $clusterPoint = $clusterCenters[$point->group];

                // this should ensure that clusterPoint and therfore the center group is never 0. But this is not true.
                $clusterPoint->group = (
                    $clusterPoint->group + 1
                );

                for ($i = 0; $i < $coordinates; ++$i) {
                    $clusterPoint->setCoordinate($i, $clusterPoint->getCoordinate($i) + $point->getCoordinate($i));
                }
            }

            foreach ($clusterCenters as $center) {
                for ($i = 0; $i < $coordinates; ++$i) {
                    /**
                     * @todo Orange-Management/phpOMS#229
                     *  Invalid center coodinate value
                     *  In some cases the center point of a cluster belongs to the group 0 in this case the coordinate value is not working correctly.
                     *  As a quick fix the value is set to `1` in such a case but probably has multiple side effects.
                     *  Maybe it makes sense to just use `$center->group + 1` or set the value to `0`.
                     */
                    $center->setCoordinate($i, $center->getCoordinate($i) / ($center->group === 0 ? 1 : $center->group));
                }
            }

            $changed = 0;
            foreach ($points as $point) {
                $min = $this->nearestClusterCenter($point, $clusterCenters)[0];

                if ($min !== $point->group) {
                    ++$changed;
                    $point->group = ($min);
                }
            }

            if ($changed <= $n * 0.001 || $n * 0.001 < 2) {
                break;
            }
        }

        foreach ($clusterCenters as $key => $center) {
            $center->group = ($key);
            $center->name = (string) $key;
        }

        $this->clusterCenters = $clusterCenters;
    }

    /**
     * Get the index and distance to the nearest cluster center
     *
     * @param PointInterface   $point          Point to get the cluster for
     * @param PointInterface[] $clusterCenters All cluster centers
     *
     * @return array [index, distance]
     *
     * @since 1.0.0
     */
    private function nearestClusterCenter(PointInterface $point, array $clusterCenters) : array
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
     * Initializae cluster centers
     *
     * @param PointInterface[] $points Points to use for the cluster center initialization
     * @param int              $n      Amount of clusters to use
     *
     * @return PointInterface[]
     *
     * @since 1.0.0
     */
    private function kpp(array $points, int $n) : array
    {
        $clusters = [clone $points[\mt_rand(0, \count($points) - 1)]];
        $d        = \array_fill(0, $n, 0.0);

        for ($i = 1; $i < $n; ++$i) {
            $sum = 0;

            foreach ($points as $key => $point) {
                $d[$key] = $this->nearestClusterCenter($point, \array_slice($clusters, 0, 5))[1];
                $sum    += $d[$key];
            }

            $sum *= \mt_rand(0, \mt_getrandmax()) / \mt_getrandmax();

            foreach ($d as $key => $di) {
                $sum -= $di;

                if ($sum <= 0) {
                    $clusters[$i] = clone $points[$key];
                }
            }
        }

        foreach ($points as $point) {
            $point->group = ($this->nearestClusterCenter($point, $clusters)[0]);
        }

        return $clusters;
    }
}
