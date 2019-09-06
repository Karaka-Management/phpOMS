<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\Algorithm\CoinMatching;

use phpOMS\Algorithm\CoinMatching\MinimumCoinProblem;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\Algorithm\CoinMatching\MinimumCoinProblem: Test coin matching a value problem
 *
 * @internal
 */
class MinimumCoinProblemTest extends \PHPUnit\Framework\TestCase
{
    public function testMinimumCoins() : void
    {
        self::assertEquals(
            [9, 6, 5, 1],
            MinimumCoinProblem::getMinimumCoinsForValueI([6, 6, 5], 17)
        );

        self::assertEquals(
            [9, 6, 5, 6, 1],
            MinimumCoinProblem::getMinimumCoinsForValueI([6, 6, 5], 17)
        );
    }
}
