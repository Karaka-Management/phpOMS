<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Business\Marketing
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Business\Marketing;

/**
 * PageRank algorithm
 *
 * @package phpOMS\Business\Marketing
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class PageRank
{
    /**
     * Damping value
     *
     * @var float
     * @since 1.0.0
     */
    private float $damping = 0.85;

    /**
     * Page rank
     *
     * @var array<mixed, float>
     * @since 1.0.0
     */
    private array $pageRanks = [];

    /**
     * Relation array
     *
     * Array of elements where every element has an array of incoming links/relations
     *
     * @var array[]
     * @since 1.0.0
     */
    private array $relations = [];

    /**
     * Amount of outgoing links from an element
     *
     * @var int[]
     * @since 1.0.0
     */
    private array $outgoing = [];

    /**
     * Constructor.
     *
     * @param array[] $relations Relations between elements (keys => link from, array => link to)
     * @param bool    $isUnique  Only consider unique relations
     * @param float   $damping   Damping value
     *
     * @since 1.0.0
     */
    public function __construct(array $relations, bool $isUnique = true, float $damping = 0.85)
    {
        $this->damping = $damping;

        foreach ($relations as $key => $relation) {
            $this->outgoing[$key] = \count($relation);

            if (!isset($this->relations[$key])) {
                $this->relations[$key] = [];
            }

            foreach ($relation as $linkTo) {
                if (!isset($this->relations[$linkTo])) {
                    $this->relations[$linkTo] = [];
                }

                if (!isset($this->outgoing[$linkTo])) {
                    $this->outgoing[$linkTo] = 0;
                }

                if (!$isUnique || !\in_array($key, $this->relations[$linkTo])) {
                    $this->relations[$linkTo][] = $key;
                }
            }
        }
    }

    /**
     * Calcualte the rank based on a start rank for the different elements
     *
     * A different start rank for different elements might make sense if the elements are not uniform from the very beginning
     *
     * @param int                      $iterations Algorithm iterations
     * @param null|array<mixed, float> $startRank  Start rank for an element
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function calculateRanks(int $iterations = 20, array $startRank = null) : array
    {
        if ($startRank !== null) {
            $this->pageRanks = $startRank;
        } else {
            foreach ($this->relations as $key => $relation) {
                $this->pageRanks[$key] = 0.0;
            }
        }

        for ($i = 0; $i < $iterations; ++$i) {
            foreach ($this->relations as $key => $relation) {
                $PR = 0.0;

                foreach ($relation as $linkFrom) {
                    $PR += $this->pageRanks[$linkFrom] / $this->outgoing[$linkFrom];
                }

                $this->pageRanks[$key] = 1 - $this->damping + $this->damping * $PR;
            }
        }

        return $this->pageRanks;
    }
}
