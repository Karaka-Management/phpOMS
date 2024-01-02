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

/**
 * Clustering points
 *
 * @package phpOMS\Algorithm\Clustering
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     ./clustering_overview.png
 * @since   1.0.0
 */
final class AffinityPropagation implements ClusteringInterface
{
    /**
     * Points of the cluster centers
     *
     * @var PointInterface[]
     * @since 1.0.0
     */
    private array $clusterCenters = [];

    /**
     * Cluster points
     *
     * Points in clusters (helper to avoid looping the cluster array)
     *
     * @var array
     * @since 1.0.0
     */
    private array $clusters = [];

    private array $similarityMatrix = [];

    private array $responsibilityMatrix = [];

    private array $availabilityMatrix = [];

    /**
     * Original points used for clusters
     *
     * @var PointInterface[]
     * @since 1.0.0
     */
    private array $points = [];

    /**
     * Create similarity matrix from points
     *
     * @param PointInterface[] $points Points to create the similarity matrix for
     *
     * @return array<int, array<int, int|float>>
     *
     * @since 1.0.0
     */
    private function createSimilarityMatrix(array $points) : array
    {
        $n                = \count($points);
        $coordinates      = \count($points[0]->coordinates);
        $similarityMatrix = \array_fill(0, $n, []);

        $temp = [];
        for ($i = 0; $i < $n - 1; ++$i) {
            for ($j = $i + 1; $j < $n; ++$j) {
                $sum = 0.0;
                for ($c = 0; $c < $coordinates; ++$c) {
                    $sum += ($points[$i]->getCoordinate($c) - $points[$j]->getCoordinate($c)) * ($points[$i]->getCoordinate($c) - $points[$j]->getCoordinate($c));
                }

                $similarityMatrix[$i][$j] = -$sum;
                $similarityMatrix[$j][$i] = -$sum;
                $temp[]                   = $similarityMatrix[$i][$j];
            }
        }

        \sort($temp);

        $size   = $n * ($n - 1) / 2;
        $median = $size % 2 === 0
            ? ($temp[(int) ($size / 2)] + $temp[(int) ($size / 2 - 1)]) / 2
            : $temp[(int) ($size / 2)];

        for ($i = 0; $i < $n; ++$i) {
            $similarityMatrix[$i][$i] = $median;
        }

        return $similarityMatrix;
    }

    /**
     * Generate clusters for points
     *
     * @param PointInterface[] $points     Points to cluster
     * @param int              $iterations Iterations for cluster generation
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function generateClusters(array $points, int $iterations = 100) : void
    {
        $this->points = $points;
        $n            = \count($points);

        $this->similarityMatrix     = $this->createSimilarityMatrix($points);
        $this->responsibilityMatrix = clone $this->similarityMatrix;
        $this->availabilityMatrix   = clone $this->similarityMatrix;

        for ($c = 0; $c < $iterations; ++$c) {
            for ($i = 0; $i < $n; ++$i) {
                for ($k = 0; $k < $n; ++$k) {
                    $max = \PHP_INT_MIN;
                    for ($j = 0; $j < $k; ++$j) {
                        if (($temp = $this->similarityMatrix[$i][$j] + $this->availabilityMatrix[$i][$j]) > $max) {
                            $max = $temp;
                        }
                    }

                    for ($j = $k + 1; $j < $n; ++$j) {
                        if (($temp = $this->similarityMatrix[$i][$j] + $this->availabilityMatrix[$i][$j]) > $max) {
                            $max = $temp;
                        }
                    }

                    $this->responsibilityMatrix[$i][$k] = (1 - 0.9) * ($this->similarityMatrix[$i][$k] - $max) + 0.9 * $this->responsibilityMatrix[$i][$k];
                }
            }

            for ($i = 0; $i < $n; ++$i) {
                for ($k = 0; $k < $n; ++$k) {
                    $sum = 0.0;

                    if ($i === $k) {
                        for ($j = 0; $j < $i; ++$j) {
                            $sum += \max(0.0, $this->responsibilityMatrix[$j][$k]);
                        }

                        for ($j += 1; $j < $n; ++$j) {
                            $sum += \max(0.0, $this->responsibilityMatrix[$j][$k]);
                        }

                        $this->availabilityMatrix[$i][$k] = (1 - 0.9) * $sum + 0.9 * $this->availabilityMatrix[$i][$k];
                    } else {
                        $max = \max($i, $k);
                        $min = \min($i, $k);

                        for ($j = 0; $j < $min; ++$j) {
                            $sum += \max(0.0, $this->responsibilityMatrix[$j][$k]);
                        }

                        for ($j = $min + 1; $j < $max; ++$j) {
                            $sum += \max(0.0, $this->responsibilityMatrix[$j][$k]);
                        }

                        for ($j = $max + 1; $j < $n; ++$j) {
                            $sum += \max(0.0, $this->responsibilityMatrix[$j][$k]);
                        }

                        $this->availabilityMatrix[$i][$k] = (1 - 0.9) * \min(0.0, $this->responsibilityMatrix[$k][$k] + $sum) + 0.9 * $this->availabilityMatrix[$i][$k];
                    }
                }
            }
        }

        // find center points (exemplar)
        for ($i = 0; $i < $n; ++$i) {
            $temp = $this->responsibilityMatrix[$i][$i] + $this->availabilityMatrix[$i][$i];

            if ($temp > 0) {
                $this->clusterCenters[$i] = $this->points[$i];
            }
        }
    }

    /**
     * Find the nearest group for a point
     *
     * @param array<int, array<int, int|float> $similarityMatrix Similarity matrix
     * @param int                              $point            Point id in the similarity matrix to compare
     *
     * @return int
     *
     * @since 1.0.0
     */
    private function findNearestGroup(array $similarityMatrix, int $point) : int
    {
        $maxSim = \PHP_INT_MIN;
        $group  = 0;

        foreach ($this->clusterCenters as $c => $_) {
            if ($similarityMatrix[$point][$c] > $maxSim) {
                $maxSim = $similarityMatrix[$point][$c];
                $group  = $c;
            }
        }

        return $group;
    }

    /**
     * {@inheritdoc}
     */
    public function cluster(PointInterface $point) : ?PointInterface
    {
        $points   = clone $this->points;
        $points[] = $point;

        $similarityMatrix = $this->createSimilarityMatrix($points);

        $c = $this->findNearestGroup(
            $similarityMatrix,
            \count($points) - 1,
        );

        return $this->clusterCenters[$c];
    }

    /**
     * {@inheritdoc}
     */
    public function getClusters() : array
    {
        if (!empty($this->clusters)) {
            return $this->clusters;
        }

        $n = \count($this->points);
        for ($i = 0; $i < $n; ++$i) {
            $group = $this->findNearestGroup($this->points, $i);

            $this->clusters[$group] = $this->points[$i];
        }

        return $this->clusters;
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
}
