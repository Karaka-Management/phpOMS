<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\CoinMatching;

use phpOMS\Algorithm\CoinMatching\MinimumCoinProblem;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\CoinMatching\MinimumCoinProblem::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\CoinMatching\MinimumCoinProblemTest: Match a value by using the minimum quantity of available sub values (Minimum Coin Problem)')]
final class MinimumCoinProblemTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A value is matched with the minimum quantity of available coins.')]
    public function testMinimumCoins() : void
    {
        self::assertEquals(
            [],
            \array_diff_key([6, 6, 5], MinimumCoinProblem::getMinimumCoinsForValueI([9, 6, 5, 6, 1], 17))
        );
    }
}
