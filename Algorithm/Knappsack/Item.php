<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\Knappsack
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Knappsack;

/**
 * Item in the knappsack
 *
 * @package phpOMS\Algorithm\Knappsack
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Item
{
    /**
     * Value of the item
     *
     * @var   float
     * @since 1.0.0
     */
    private $value = 0.0;

    /**
     * Cost of the item
     *
     * @var   float
     * @since 1.0.0
     */
    private $cost = 0.0;

    /**
     * Cosntructor.
     *
     * @param float $value Value of the item
     * @param float $cost  Cost of the item
     *
     * @since 1.0.0
     */
    public function __construct(float $value, float $cost)
    {
        $this->value = $value;
        $this->cost  = $cost;
    }

    /**
     * Get value of the item
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getValue() : float
    {
        return $this->value;
    }

    /**
     * Get value of the item
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getCost() : float
    {
        return $this->cost;
    }
}
