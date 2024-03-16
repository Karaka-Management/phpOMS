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

use phpOMS\Math\Matrix\Matrix;

/**
 * Model based collaborative filtering
 *
 * @package phpOMS\Business\Recommendation
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     https://realpython.com/build-recommendation-engine-collaborative-filtering/
 * @since   1.0.0
 */
final class ModelCF
{
    /**
     * Constructor
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Calculate the score of a user <-> item match.
     *
     * This function calculates how much a user likes a certain item (product, movie etc.)
     *
     * $user and $item can also be Vectors resulting in a individual evaluation
     * e.g. the user matrix contains a user in every row, every column represents a score for a certain attribute
     * the item matrix contains in every row a score for how much it belongs to a certain attribute. Each column represents an item.
     * example: users columns define how much a user likes a certain movie genre and the item rows define how much this movie belongs to a certain genre.
     * the multiplication gives a score of how much the user may like that movie.
     * A significant amount of attributes are required to calculate a good match
     *
     * @param array<int|string, array<int|float>> $users A mxa matrix where each "m" defines how much the user likes a certain attribute type and "a" defines different users
     * @param array<int|string, array<int|float>> $items A bxm matrix where each "b" defines a item and "m" defines how much it belongs to a certain attribute type
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function score(array $users, array $items) : array
    {
        $matrix = [];

        foreach ($users as $uid => $userrow) {
            foreach ($items as $iid => $itemrow) {
                $matrix[$uid][$iid] = 0.0;

                $userrow = \array_values($userrow);
                $itemrow = \array_values($itemrow);

                foreach ($userrow as $idx => $user) {
                    $matrix[$uid][$iid] += $user * $itemrow[$idx];
                }
            }
        }

        return $matrix;
    }
}
