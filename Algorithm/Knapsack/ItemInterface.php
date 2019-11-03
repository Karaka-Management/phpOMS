<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\Knapsack
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */

declare(strict_types=1);

namespace phpOMS\Algorithm\Knapsack;

/**
 * Item interface.
 *
 * @package phpOMS\Algorithm\Knapsack;
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface ItemInterface
{
    /**
     * Get value of the item
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getValue() : float;

    /**
     * Get value of the item
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getCost() : float;

    /**
     * Get the name of the item
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName() : string;
}