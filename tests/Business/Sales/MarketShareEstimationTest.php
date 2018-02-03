<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
namespace phpOMS\tests\Business\Sales;

use phpOMS\Business\Sales\MarketShareEstimation;

class MarketShareEstimationTest extends \PHPUnit\Framework\TestCase
{
    public function testZipfRank()
    {
        self::assertEquals(13, MarketShareEstimation::getRankFromMarketShare(1000, 0.01));
        self::assertEquals(19, MarketShareEstimation::getRankFromMarketShare(100, 0.01));
        self::assertEquals(8, MarketShareEstimation::getRankFromMarketShare(100000, 0.01));
    }

    public function testZipfShare()
    {
        self::assertTrue(abs(0.01 - MarketShareEstimation::getMarketShareFromRank(1000, 13)) < 0.01);
        self::assertTrue(abs(0.01 - MarketShareEstimation::getMarketShareFromRank(100, 19)) < 0.01);
        self::assertTrue(abs(0.01 - MarketShareEstimation::getMarketShareFromRank(100000, 8)) < 0.01);
    }
}
