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

/**
 * Clustering points
 *
 * The parent category of this clustering algorithm is hierarchical clustering.
 *
 * @package phpOMS\Algorithm\Clustering
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     ./AgglomerativeClustering.php
 * @see     ./clustering_overview.png
 * @see     https://en.wikipedia.org/wiki/Hierarchical_clustering
 * @since   1.0.0
 *
 * @todo Implement
 */
final class DivisiveClustering implements ClusteringInterface
{
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
    public function cluster(PointInterface $point) : ?PointInterface
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
