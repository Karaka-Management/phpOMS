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
 * Point interface.
 *
 * @property int    $group       Group
 * @property string $name        Name
 * @property array  $coordinates Coordinates
 *
 * @package phpOMS\Algorithm\Clustering;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface PointInterface
{
    /**
     * Get the point coordinates
     *
     * @return array<int, int|float>
     *
     * @since 1.0.0
     */
    public function getCoordinates() : array;

    /**
     * Get the coordinate of the point
     *
     * @param int $index Index of the coordinate (e.g. 0 = x);
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    public function getCoordinate(int $index) : int | float;

    /**
     * Set the coordinate of the point
     *
     * @param int       $index Index of the coordinate (e.g. 0 = x);
     * @param int|float $value Value of the coordinate
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setCoordinate(int $index, int | float $value) : void;

    /**
     * Check if two points are equal
     *
     * @param self $point Point to compare with
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isEquals(self $point) : bool;
}
