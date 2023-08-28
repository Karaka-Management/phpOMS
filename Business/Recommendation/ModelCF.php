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
 * @since   1.0.0
 * @see     https://realpython.com/build-recommendation-engine-collaborative-filtering/
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

    // $user and $item can also be Vectors resulting in a individual evaluation
    // e.g. the user matrix contains a user in every row, every column represents a score for a certain attribute
    // the item matrix contains in every row a score for how much it belongs to a certain attribute. Each column represents an item.
    // example: users columns define how much a user likes a certain movie genre and the item rows define how much this movie belongs to a certain genre.
    // the multiplication gives a score of how much the user may like that movie.
    // A segnificant amount of attributes are required to calculate a good match
    public static function score(Matrix $users, Matrix $items) : array
    {
        return $users->mult($items)->getMatrix();
    }
}
