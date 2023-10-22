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
 * @see     https://realpython.com/build-recommendation-engine-collaborative-filtering/
 * @since   1.0.0
 */
final class MemoryCF
{
    /**
     * All rankings
     *
     * @var array<array>
     * @since 1.0.0
     */
    private array $rankings = [];

    /**
     * Constructor.
     *
     * @param array<array> $rankings Array of item ratings by users (or reverse to find users)
     *
     * @since 1.0.0
     */
    public function __construct(array $rankings)
    {
        $this->rankings = $this->normalizeRanking($rankings);
    }

    /**
     * Normalize all ratings.
     *
     * This is necessary because some users my give lower or higher ratings on average (bias).
     *
     * @param array<array> $rankings Item ratings/rankings
     *
     * @return array<array>
     *
     * @since 1.0.0
     */
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

    /**
     * Euclidean distance between users
     *
     * @param array        $ranking  Rating to find the distance for
     * @param array<array> $rankings All ratings to find the distance to
     *
     * @return float[]
     *
     * @since 1.0.0
     */
    private function euclideanDistance(array $ranking, array $rankings) : array
    {
        $distances = [];
        foreach ($rankings as $idx => $r) {
            $distances[$idx] = \abs(MetricsND::euclidean($ranking, $r));
        }

        return $distances;
    }

    /**
     * Cosine distance between users
     *
     * @param array        $ranking  Rating to find the distance for
     * @param array<array> $rankings All ratings to find the distance to
     *
     * @return float[]
     *
     * @since 1.0.0
     */
    private function cosineDistance(array $ranking, array $rankings) : array
    {
        $distances = [];
        foreach ($rankings as $idx => $r) {
            $distances[$idx] = \abs(MetricsND::cosine($ranking, $r));
        }

        return $distances;
    }

    /**
     * Assign a item rank/rating based on the distance to other items
     *
     * @param string       $itemId    Id of the item to rank
     * @param array        $distances Distance to other users
     * @param array<array> $users     All user ratings
     * @param int          $size      Only consider the top n distances (best matches with other users)
     *
     * @return float Estimated item rank/rating based on similarity to other users
     *
     * @since 1.0.0
     */
    private function weightedItemRank(string $itemId, array $distances, array $users, int $size) : float
    {
        $rank  = 0.0;
        $count = 0;
        foreach ($distances as $uId => $_) {
            if ($count >= $size) {
                break;
            }

            if (!isset($users[$itemId])) {
                continue;
            }

            ++$count;
            $rank += $users[$uId][$itemId];
        }

        return $count === 0 ? 0.0 : $rank / $count;
    }

    /**
     * Find potential items/users which are a good match for a user/item.
     *
     * The algorithm uses the ratings of a a user and tries to find other users who have similar rating behavior and then searches for high rated items that the user doesn't have yet.
     *
     * This can be used to find items for a specific user (aka might be interested in) or to find users who might be interested in this item
     *
     * option 1 - find items
     *      ranking[itemId] = itemRank (how much does specific user like item)
     *      rankings[userId][itemId] = itemRank
     *
     * option 2 - find user
     *      ranking[userId] = itemRank (how much does user like specific item)
     *      rankings[itemId][userId] = itemRank
     * option 1 searches for items, option 2 searches for users
     *
     * @param array $ranking Array of item ratings (e.g. products, movies, ...)
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function bestMatch(array $ranking, int $size = 10) : array
    {
        $ranking = $this->normalizeRanking([$ranking]);
        $ranking = $ranking[0];

        $euclidean = $this->euclideanDistance($ranking, $this->rankings);
        $cosine    = $this->cosineDistance($ranking, $this->rankings);

        \asort($euclidean);
        \asort($cosine);

        $size    = \min($size, \count($this->rankings));
        $matches = [];

        $distancePointer = \array_keys($euclidean);
        $anglePointer    = \array_keys($cosine);

        // Inspect items of the top n comparable users
        for ($i = 0; $i < $size; ++$i) {
            $uId       = $i % 2 === 0 ? $distancePointer[$i] : $anglePointer[$i];
            $distances = $i % 2 === 0 ? $euclidean : $cosine;

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

        return \array_reverse($matches, true);
    }
}
