<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Business\Marketing
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Business\Marketing;

/**
 * Marketing Metrics
 *
 * This class provided basic marketing metric calculations
 *
 * @package phpOMS\Business\Marketing
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Metrics
{
    /**
     * Constructure
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Calculate customer retention
     *
     * @latex  r = \frac{ce - cn}{cs}
     *
     * @param int $ce Customer at the end of the period
     * @param int $cn New customers during period
     * @param int $cs Customers at the start of the period
     *
     * @return float Returns the customer retention
     *
     * @since 1.0.0
     */
    public static function getCustomerRetention(int $ce, int $cn, int $cs) : float
    {
        return ($ce - $cn) / $cs;
    }
}
