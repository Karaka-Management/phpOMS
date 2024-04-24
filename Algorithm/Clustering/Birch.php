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
 * @package phpOMS\Algorithm\Clustering
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     ./clustering_overview.png
 * @since   1.0.0
 *
 * @todo Implement
 */
final class Birch implements ClusteringInterface
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
