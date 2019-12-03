<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);
namespace phpOMS\tests\Business\Sales;

use phpOMS\Business\Sales\MarketShareEstimation;

/**
 * @testdox phpOMS\tests\Business\Sales\MarketShareEstimationTest: Market share calculations
 *
 * @internal
 */
class MarketShareEstimationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The rank calculated with Zipf is correct
     * @group framework
     */
    public function testZipfRank() : void
    {
        self::assertEquals(13, MarketShareEstimation::getRankFromMarketShare(1000, 0.01));
        self::assertEquals(19, MarketShareEstimation::getRankFromMarketShare(100, 0.01));
        self::assertEquals(8, MarketShareEstimation::getRankFromMarketShare(100000, 0.01));
    }

    /**
     * @testdox The market share by rank calculated with Zipf is correct
     * @group framework
     */
    public function testZipfShare() : void
    {
        self::assertTrue(\abs(0.01 - MarketShareEstimation::getMarketShareFromRank(1000, 13)) < 0.01);
        self::assertTrue(\abs(0.01 - MarketShareEstimation::getMarketShareFromRank(100, 19)) < 0.01);
        self::assertTrue(\abs(0.01 - MarketShareEstimation::getMarketShareFromRank(100000, 8)) < 0.01);
    }
}
