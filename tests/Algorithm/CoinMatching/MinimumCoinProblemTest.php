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

namespace phpOMS\tests\Algorithm\CoinMatching;

use phpOMS\Algorithm\CoinMatching\MinimumCoinProblem;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\CoinMatching\MinimumCoinProblemTest: Match a value by using the minimum quantity of available sub values (Minimum Coin Problem)
 *
 * @internal
 */
class MinimumCoinProblemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A value is matched with the minimum quantity of avialable coins.
     * @covers phpOMS\Algorithm\CoinMatching\MinimumCoinProblem
     * @group framework
     */
    public function testMinimumCoins() : void
    {
        self::assertEquals(
            [],
            \array_diff_key([6, 6, 5], MinimumCoinProblem::getMinimumCoinsForValueI([9, 6, 5, 6, 1], 17))
        );
    }
}
