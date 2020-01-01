<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\Clustering
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */

declare(strict_types=1);

namespace phpOMS\Algorithm\Clustering;

/**
 * Point interface.
 *
 * @package phpOMS\Algorithm\Clustering;
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface PointInterface
{
    /**
     * Get the point coordinates
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getCoordinates() : array;

    /**
     * Get the coordinate of the point
     *
     * @param mixed $index Index of the coordinate (e.g. 0 = x);
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    public function getCoordinate($index);

    /**
     * Set the coordinate of the point
     *
     * @param mixed $index Index of the coordinate (e.g. 0 = x);
     * @param mixed $value Value of the coordinate
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setCoordinate($index, $value) : void;

    /**
     * Get group this point belongs to
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getGroup() : int;

    /**
     * Set the group this point belongs to
     *
     * @param int $group Group or cluster this point belongs to
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setGroup(int $group) : void;

    /**
     * Set the point name
     *
     * @param string $name Name of the point
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setName(string $name) : void;

    /**
     * Get the name of the point
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName() : string;
}
