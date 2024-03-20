<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\PathFinding;

use phpOMS\Algorithm\PathFinding\Heuristic;
use phpOMS\Algorithm\PathFinding\HeuristicType;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\PathFinding\Heuristic::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\PathFinding\HeuristicTest: Heuristic for path finding')]
final class HeuristicTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The heuristics return the correct metric results')]
    public function testHeuristics() : void
    {
        self::assertEquals(
            10.0,
            Heuristic::metric(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], HeuristicType::MANHATTAN)
        );

        self::assertEqualsWithDelta(
            7.615773,
            Heuristic::metric(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], HeuristicType::EUCLIDEAN),
            0.1
        );

        self::assertEquals(
            7.0,
            Heuristic::metric(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], HeuristicType::CHEBYSHEV)
        );

        self::assertEqualsWithDelta(
            10.0,
            Heuristic::metric(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], HeuristicType::MINKOWSKI),
            0.1
        );

        self::assertEqualsWithDelta(
            1.333,
            Heuristic::metric(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], HeuristicType::CANBERRA),
            0.1
        );

        self::assertEqualsWithDelta(
            0.625,
            Heuristic::metric(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], HeuristicType::BRAY_CURTIS),
            0.1
        );

        self::assertEqualsWithDelta(
            8.24264,
            Heuristic::metric(['x' => 0, 'y' => 3], ['x' => 7, 'y' => 6], HeuristicType::OCTILE),
            0.1
        );
    }
}
