<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Business\Sales
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Business\Sales;

/**
 * Order suggestion calculations
 *
 * @package phpOMS\Business\Sales
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class OrderSuggestion
{
    /**
     * Calculate the optimal order quantity using the Andler formula
     */
    public static function andler(float $annualQuantity, float $orderCosts, float $unitPrice, float $warehousingCostRatio) : float
    {
        return \sqrt(2 * $annualQuantity * $orderCosts / ($unitPrice * $warehousingCostRatio));
    }
}
