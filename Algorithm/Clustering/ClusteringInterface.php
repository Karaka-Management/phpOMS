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
 * Clustering interface.
 *
 * @package phpOMS\Algorithm\Clustering;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface ClusteringInterface
{
    /**
     * Get cluster centroids
     *
     * @return Point[]
     *
     * @since 1.0.0
     */
    public function getCentroids() : array;

    /**
     * Get cluster assignments of the training data
     *
     * @return Point[]
     *
     * @since 1.0.0
     */
    public function getClusters() : array;

    /**
     * Cluster a single point
     *
     * This point doesn't have to be in the training data.
     *
     * @param Point $point Point to cluster
     *
     * @return null|Point
     *
     * @since 1.0.0
     */
    public function cluster(Point $point) : ?Point;

    /**
     * Get noise data.
     *
     * Data points from the training data that are not part of a cluster.
     *
     * @return Point[]
     *
     * @since 1.0.0
     */
    public function getNoise() : array;

    // Not possible to interface due to different implementations
    // public function generateClusters(...) : void
}
