<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Business\Recommendation
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Business\Recommendation;

use phpOMS\Math\Topology\MetricsND;

/**
 * Memory based collaborative filtering
 *
 * Items or potential customers are found based on how much they like certain items.
 *
 * This requires a item/product rating of some sort in the backend.
 * Such a rating could be either manual user ratings or a rating based on how often it is purchased or how long it is used.
 * Most likely a combination is required.
 *
 * @package phpOMS\Business\Recommendation
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @see     https://realpython.com/build-recommendation-engine-collaborative-filtering/
 */
final class MemoryCF
{
    private array $rankings = [];

    public function __construc(array $rankings)
    {
        $this->rankings = $this->normalizeRanking($rankings);
    }

    private function normalizeRanking(array $rankings) : array
    {
        foreach ($rankings as $idx => $items) {
            $avg = \array_sum($items) / \count($items);

            foreach ($items as $idx2 => $_) {
                $rankings[$idx][$idx2] -= $avg;
            }
        }

        return $rankings;
    }

    // Used to find similar users
    public function euclideanDistance(array $ranking, array $rankings) : array
    {
        $distances = [];
        foreach ($rankings as $idx => $r) {
            $distances[$idx] = \abs(MetricsND::euclidean($ranking, $r));
        }

        return $distances;
    }

    // Used to find similar users
    public function cosineDistance(array $ranking, array $rankings) : array
    {
        $distances = [];
        foreach ($rankings as $idx => $r) {
            $distances[$idx] = \abs(MetricsND::cosine($ranking, $r));
        }

        return $distances;
    }

    private function weightedItemRank(string $itemId, array $distances, array $users, int $size) : float
    {
        $rank = 0.0;
        $count = 0;
        foreach ($distances as $uId => $_) {
            if ($count >= $size) {
                break;
            }

            if (!isset($user[$itemId])) {
                continue;
            }

            ++$count;
            $rank += $users[$uId][$itemId];
        }

        return $rank / $count;
    }

    // This can be used to find items for a specific user (aka might be interested in) or to find users who might be interested in this item
    // option 1 - find items
    //      ranking[itemId] = itemRank (how much does specific user like item)
    //      rankings[userId][itemId] = itemRank
    //
    // option 2 - find user
    //      ranking[userId] = itemRank (how much does user like specific item)
    //      rankings[itemId][userId] = itemRank
    // option 1 searches for items, option 2 searches for users
    public function bestMatch(array $ranking, int $size = 10) : array
    {
        $ranking  = $this->normalizeRanking([$ranking]);
        $ranking  = $ranking[0];

        $euclidean = $this->euclideanDistance($ranking, $this->rankings);
        $cosine = $this->cosineDistance($ranking, $this->rankings);

        \asort($euclidean);
        \asort($cosine);

        $size = \min($size, \count($this->rankings));
        $matches = [];

        $distancePointer = \array_keys($euclidean);
        $anglePointer    = \array_keys($cosine);

        // Inspect items of the top n comparable users
        for ($i = 1; $i <= $size; ++$i) {
            $index = (int) ($i / 2) - 1;

            $uId = $i % 2 === 1 ? $distancePointer[$index] : $anglePointer[$index];
            $distances = $i % 2 === 1 ? $euclidean : $cosine;
            foreach ($this->rankings[$uId] as $iId => $_) {
                // Item is not already in dataset and not in historic dataset (we are only interested in new)
                if (isset($matches[$iId]) || isset($ranking[$iId])) {
                    continue;
                }

                // Calculate the expected rating the user would give based on what the best comparable users did
                $matches[$iId] = $this->weightedItemRank($iId, $distances, $this->rankings, $size);
            }
        }

        \asort($matches);
        $matches = \array_reverse($matches, true);

        return $matches;
    }
}
