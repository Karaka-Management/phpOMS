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

use phpOMS\Algorithm\PathFinding\Grid;
use phpOMS\Algorithm\PathFinding\HeuristicType;
use phpOMS\Algorithm\PathFinding\JumpPointNode;
use phpOMS\Algorithm\PathFinding\JumpPointSearch;
use phpOMS\Algorithm\PathFinding\MovementType;
use phpOMS\Algorithm\PathFinding\Path;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\PathFinding\JumpPointSearch::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\PathFinding\JumpPointSearchTest: JumpPoint path finding')]
final class JumpPointSearchTest extends \PHPUnit\Framework\TestCase
{
    private array $gridArray = [
        [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
        [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0,],
        [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
        [0, 0, 9, 9, 9, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0,],
        [0, 0, 0, 0, 9, 9, 9, 9, 9, 0, 9, 0, 0, 0, 0,],
        [0, 0, 1, 0, 9, 0, 0, 0, 0, 0, 9, 0, 9, 9, 9,],
        [0, 0, 0, 0, 9, 0, 0, 9, 9, 9, 9, 0, 0, 0, 0,],
        [0, 0, 9, 9, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
        [0, 0, 0, 9, 0, 0, 0, 9, 0, 0, 9, 9, 9, 9, 0,],
        [0, 0, 0, 9, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0,],
        [0, 0, 0, 9, 0, 0, 0, 9, 0, 0, 9, 9, 0, 0, 0,],
        [0, 0, 0, 0, 0, 9, 9, 9, 0, 0, 9, 2, 0, 0, 0,],
        [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 9, 9, 0, 0, 0,],
        [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
        [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
    ];

    /**
     * Render a maze with ASCII symbols
     *
     * @param array $grid Maze grid
     *
     * @return void
     */
    private function renderMaze(array $grid) : void
    {
        echo "\n";
        foreach ($grid as $y => $row) {
            echo "[";

            foreach ($row as $x => $value) {
                if ($value === 9) {
                    echo "\e[0;31m" . $value . "\e[0m, ";
                } elseif ($value === 3) {
                    echo "\e[1;32m" . $value . "\e[0m, ";
                } else {
                    echo "" . $value . ", ";
                }
            }

            echo "],\n";
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The correct path is found for diagonal movement')]
    public function testPathFindingDiagonal() : void
    {
        $grid = Grid::createGridFromArray($this->gridArray, JumpPointNode::class);
        $path = JumpPointSearch::findPath(
            2, 5,
            11, 11,
            $grid, HeuristicType::EUCLIDEAN, MovementType::DIAGONAL
        );

        $expanded = $path->expandPath();

        foreach ($expanded as $node) {
            $this->gridArray[$node->getY()][$node->getX()] = 3;
        }

        // Visualization of path
        //$this->renderMaze($this->gridArray);

        self::assertEqualsWithDelta(20.55634, $path->getLength(), 0.001);

        self::assertEquals([
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 9, 9, 9, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 9, 9, 9, 9, 9, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 3, 0, 9, 0, 0, 0, 0, 0, 9, 0, 9, 9, 9, ],
            [0, 3, 0, 0, 9, 0, 0, 9, 9, 9, 9, 0, 0, 0, 0, ],
            [0, 3, 9, 9, 9, 0, 0, 3, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 3, 9, 0, 0, 3, 9, 3, 0, 9, 9, 9, 9, 0, ],
            [0, 0, 3, 9, 0, 3, 0, 9, 0, 3, 3, 3, 0, 0, 0, ],
            [0, 0, 3, 9, 3, 0, 0, 9, 0, 0, 9, 9, 3, 0, 0, ],
            [0, 0, 0, 3, 0, 9, 9, 9, 0, 0, 9, 3, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 9, 9, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
        ], $this->gridArray);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The correct path is found for straight movement')]
    public function testPathFindingStraight() : void
    {
        $grid = Grid::createGridFromArray($this->gridArray, JumpPointNode::class);
        $path = JumpPointSearch::findPath(
            2, 5,
            11, 11,
            $grid, HeuristicType::EUCLIDEAN, MovementType::STRAIGHT
        );

        $expanded = $path->expandPath();

        foreach ($expanded as $node) {
            $this->gridArray[$node->getY()][$node->getX()] = 3;
        }

        // Visualization of path
        //$this->renderMaze($this->gridArray);

        self::assertEqualsWithDelta(27.0, $path->getLength(), 0.001);

        self::assertEquals([
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 9, 9, 9, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 9, 9, 9, 9, 9, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 3, 0, 9, 0, 0, 0, 0, 0, 9, 0, 9, 9, 9, ],
            [0, 3, 3, 0, 9, 0, 0, 9, 9, 9, 9, 0, 0, 0, 0, ],
            [0, 3, 9, 9, 9, 3, 3, 3, 3, 0, 0, 0, 0, 0, 0, ],
            [0, 3, 0, 9, 3, 3, 0, 9, 3, 0, 9, 9, 9, 9, 0, ],
            [0, 3, 0, 9, 3, 0, 0, 9, 3, 3, 3, 3, 3, 0, 0, ],
            [0, 3, 0, 9, 3, 0, 0, 9, 0, 0, 9, 9, 3, 0, 0, ],
            [0, 3, 3, 3, 3, 9, 9, 9, 0, 0, 9, 3, 3, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 9, 9, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
        ], $this->gridArray);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The correct path is found for diagonal movement [one obstacle]')]
    public function testPathFindingDiagonalOneObstacle() : void
    {
        $grid = Grid::createGridFromArray($this->gridArray, JumpPointNode::class);
        $path = JumpPointSearch::findPath(
            2, 5,
            11, 11,
            $grid, HeuristicType::EUCLIDEAN, MovementType::DIAGONAL_ONE_OBSTACLE
        );

        $expanded = $path->expandPath();

        foreach ($expanded as $node) {
            $this->gridArray[$node->getY()][$node->getX()] = 3;
        }

        // Visualization of path
        //$this->renderMaze($this->gridArray);

        self::assertEqualsWithDelta(20.55634, $path->getLength(), 0.001);

        self::assertEquals([
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 9, 9, 9, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 9, 9, 9, 9, 9, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 3, 0, 9, 0, 0, 0, 0, 0, 9, 0, 9, 9, 9, ],
            [0, 3, 0, 0, 9, 0, 0, 9, 9, 9, 9, 0, 0, 0, 0, ],
            [0, 3, 9, 9, 9, 0, 0, 3, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 3, 9, 0, 0, 3, 9, 3, 0, 9, 9, 9, 9, 0, ],
            [0, 0, 3, 9, 0, 3, 0, 9, 0, 3, 3, 3, 0, 0, 0, ],
            [0, 0, 3, 9, 3, 0, 0, 9, 0, 0, 9, 9, 3, 0, 0, ],
            [0, 0, 0, 3, 0, 9, 9, 9, 0, 0, 9, 3, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 9, 9, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
        ], $this->gridArray);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The correct path is found for diagonal movement [no obstacle]')]
    public function testPathFindingDiagonalNoObstacle() : void
    {
        $grid = Grid::createGridFromArray($this->gridArray, JumpPointNode::class);
        $path = JumpPointSearch::findPath(
            2, 5,
            11, 11,
            $grid, HeuristicType::EUCLIDEAN, MovementType::DIAGONAL_NO_OBSTACLE
        );

        $expanded = $path->expandPath();

        foreach ($expanded as $node) {
            $this->gridArray[$node->getY()][$node->getX()] = 3;
        }

        // Visualization of path
        //$this->renderMaze($this->gridArray);

        self::assertEqualsWithDelta(22.89949, $path->getLength(), 0.001);

        self::assertEquals([
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 9, 9, 9, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 9, 9, 9, 9, 9, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 3, 0, 9, 0, 0, 0, 0, 0, 9, 0, 9, 9, 9, ],
            [0, 3, 0, 0, 9, 0, 0, 9, 9, 9, 9, 0, 0, 0, 0, ],
            [0, 3, 9, 9, 9, 0, 0, 3, 3, 0, 0, 0, 0, 0, 0, ],
            [0, 3, 0, 9, 0, 0, 3, 9, 0, 3, 9, 9, 9, 9, 0, ],
            [0, 0, 3, 9, 0, 3, 0, 9, 0, 0, 3, 3, 3, 0, 0, ],
            [0, 0, 3, 9, 3, 0, 0, 9, 0, 0, 9, 9, 3, 0, 0, ],
            [0, 0, 3, 3, 3, 9, 9, 9, 0, 0, 9, 3, 3, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 9, 9, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
        ], $this->gridArray);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid start or end node returns the grid')]
    public function testInvalidStartEndNode() : void
    {
        $grid = Grid::createGridFromArray($this->gridArray, JumpPointNode::class);

        self::assertEquals(
            new Path($grid),
            $path = JumpPointSearch::findPath(
                999, 999,
                -999, -999,
                $grid, HeuristicType::EUCLIDEAN, MovementType::DIAGONAL_NO_OBSTACLE
            )
        );
    }
}
