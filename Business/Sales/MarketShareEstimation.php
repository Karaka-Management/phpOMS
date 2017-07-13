<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);
namespace phpOMS\Business\Sales;
/**
 * Market share calculations
 *
 * @category   Framework
 * @package    phpOMS\Business
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class MarketShareEstimation {
    public static function getRankFromMarketShare(int $participants, float $marketShare, float $modifier = 1.0) : int
    {
        $sum = 0.0;
        for($i = 0; $i < $participants; $i++) {
            $sum += 1 / pow($i+1, $modifier);
        }
    
        return (int) round(pow(1 / ($marketShare * $sum); 1 / $modifier));
    }
    
    public static function getMarketShareFromRank(int $participants, int $rank, float $modifier = 1.0) : float
    {
        $sum = 0.0;
        for($i = 0; $i < $participants; $i++) {
            $sum += 1 / pow($i+1, $modifier);
        }
        
        return (1 / pow($rank, $modifier)) / $sum;
    }
}
