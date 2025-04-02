<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Algorithm\Clustering
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Clustering;

use phpOMS\Math\Topology\MetricsND;

/**
 * Clustering points
 *
 * The parent category of this clustering algorithm is hierarchical clustering.
 *
 * @package phpOMS\Algorithm\Clustering
 * @license Base: MIT Copyright (c) 2020 Greene Laboratory
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @see     ./DivisiveClustering.php
 * @see     ./clustering_overview.png
 * @see     https://en.wikipedia.org/wiki/Hierarchical_clustering
 * @see     https://github.com/greenelab/hclust/blob/master/README.md
 * @since   1.0.0
 *
 * @todo Implement
 * @todo Implement missing linkage functions
 */
final class AgglomerativeClustering implements ClusteringInterface
{
    /**
     * Metric to calculate the distance between two points
     *
     * @var \Closure
     * @since 1.0.0
     */
    public \Closure $metric;

    /**
     * Metric to calculate the distance between two points
     *
     * @var \Closure
     * @since 1.0.0
     */
    public \Closure $linkage;

    /**
     * Constructor
     *
     * @param null|\Closure $metric metric to use for the distance between two points
     *
     * @since 1.0.0
     */
    public function __construct(?\Closure $metric = null, ?\Closure $linkage = null)
    {
        $this->metric = $metric ?? function (Point $a, Point $b) {
            $aCoordinates = $a->coordinates;
            $bCoordinates = $b->coordinates;

            return MetricsND::euclidean($aCoordinates, $bCoordinates);
        };

        $this->linkage = $linkage ?? function (array $a, array $b, array $distances) {
            return self::averageDistanceLinkage($a, $b, $distances);
        };
    }

    /**
     * Maximum/Complete-Linkage clustering
     */
    public static function maximumDistanceLinkage(array $setA, array $setB, array $distances) : float
    {
        $max = \PHP_INT_MIN;
        foreach ($setA as $a) {
            foreach ($setB as $b) {
                if ($distances[$a][$b] > $max) {
                    $max = $distances[$a][$b];
                }
            }
        }

        return $max;
    }

    /**
     * Minimum/Single-Linkage clustering
     */
    public static function minimumDistanceLinkage(array $setA, array $setB, array $distances) : float
    {
        $min = \PHP_INT_MAX;
        foreach ($setA as $a) {
            foreach ($setB as $b) {
                if ($distances[$a][$b] < $min) {
                    $min = $distances[$a][$b];
                }
            }
        }

        return $min;
    }

    /**
     * Unweighted average linkage clustering (UPGMA)
     */
    public static function averageDistanceLinkage(array $setA, array $setB, array $distances) : float
    {
        $distance = 0;
        foreach ($setA as $a) {
            $distance += \array_sum($distances[$a]);
        }

        return $distance / \count($setA) / \count($setB);
    }

    /**
     * {@inheritdoc}
     */
    public function getCentroids() : array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getClusters() : array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function cluster(Point $point) : ?Point
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getNoise() : array
    {
        return [];
    }
}
