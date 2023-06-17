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
 * Market share calculations (Zipf function)
 *
 * This class can be used to calculate the market share based on a rank or vice versa
 * the rank based on a marketshare in a Zipf distributed market.
 *
 * @package phpOMS\Business\Sales
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class MarketShareEstimation
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
     * Calculate rank (r) based on market share (m)
     *
     * @latex  r = \sqrt[s]{\frac{1}{m \times \sum_{n=1}^N{\frac{1}{n^{s}}}}}
     *
     * @param int   $participants The amount of existing participants in the market or compentitors (N)
     * @param float $marketShare  The absolute own market share (m)
     * @param float $modifier     Distribution modifier (s)
     *
     * @return int Returns the rank
     *
     * @since 1.0.0
     */
    public static function getRankFromMarketShare(int $participants, float $marketShare, float $modifier = 1.0) : int
    {
        $sum = 0.0;
        for ($i = 0; $i < $participants; ++$i) {
            $sum += 1 / \pow($i + 1, $modifier);
        }

        return (int) \round(\pow(1 / ($marketShare * $sum), 1 / $modifier));
    }

    /**
     * Calculate market share (m) based on rank (r)
     *
     * @latex  m = \frac{\frac{1}{r^{s}}}{\sum_{n=1}^N{\frac{1}{n^{s}}}}
     *
     * @param int   $participants The amount of existing participants in the market or compentitors (N)
     * @param int   $rank         The absolute own rank in the market (r)
     * @param float $modifier     Distribution modifier (s)
     *
     * @return float Returns the Market share
     *
     * @since 1.0.0
     */
    public static function getMarketShareFromRank(int $participants, int $rank, float $modifier = 1.0) : float
    {
        $sum = 0.0;
        for ($i = 0; $i < $participants; ++$i) {
            $sum += 1 / \pow($i + 1, $modifier);
        }

        return (1 / \pow($rank, $modifier)) / $sum;
    }
}
