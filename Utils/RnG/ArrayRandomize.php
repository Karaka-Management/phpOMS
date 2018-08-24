<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils\RnG
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\RnG;

/**
 * Array randomizer class
 *
 * @package    phpOMS\Utils\RnG
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class ArrayRandomize
{
    /**
     * Yates array shuffler.
     *
     * @param array $arr Array to randomize. Array must NOT be associative
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function yates(array $arr) : array
    {
        $shuffled = [];

        while (!empty($arr)) {
            $rnd        = (int) array_rand($arr);
            $shuffled[] = $arr[$rnd] ?? null;
            array_splice($arr, $rnd, 1);
        }

        return $shuffled;
    }

    /**
     * Knuths array shuffler.
     *
     * @param array $arr Array to randomize
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function knuth(array $arr) : array
    {
        $shuffled = [];

        for ($i = \count($arr) - 1; $i > 0; $i--) {
            $rnd            = mt_rand(0, $i);
            $shuffled[$i]   = $arr[$rnd];
            $shuffled[$rnd] = $arr[$i];
        }

        return $shuffled;
    }
}
