<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);
namespace phpOMS\Business\Sales;
/**
 * Market share calculations (Zipf function)
 *
 * @category   Framework
 * @package    phpOMS\Business
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class MarketShareEstimation {
    /**
     * Calculate rank (r) based on marketshare (m)
     *
     * @latex  r = \sqrt[s]{\frac{1}{m \times \sum_{n=1}^N{\frac{1}{n^{s}}}}}
     *
     * @param int $participants (p)
     * @param float $marketShare (m)
     * @param float $modifier (s)
     *
     * @return int
     *
     * @since  1.0.0
     */
    public static function getRankFromMarketShare(int $participants, float $marketShare, float $modifier = 1.0) : int
    {
        $sum = 0.0;
        for($i = 0; $i < $participants; $i++) {
            $sum += 1 / pow($i+1, $modifier);
        }
    
        return (int) round(pow(1 / ($marketShare * $sum), 1 / $modifier));
    }
    
    /**
     * Calculate marketshare (m) based on rank (r)
     *
     * @latex  m = \frac{\frac{1}{r^{s}}}{\sum_{n=1}^N{\frac{1}{n^{s}}}}
     *
     * @param int $participants (p)
     * @param int $rank (r)
     * @param float $modifier (s)
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getMarketShareFromRank(int $participants, int $rank, float $modifier = 1.0) : float
    {
        $sum = 0.0;
        for($i = 0; $i < $participants; $i++) {
            $sum += 1 / pow($i+1, $modifier);
        }
        
        return (1 / pow($rank, $modifier)) / $sum;
    }
}
