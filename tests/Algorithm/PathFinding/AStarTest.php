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

namespace phpOMS\tests\Algorithm\PathFinding;

use phpOMS\Algorithm\PathFinding\AStar;
use phpOMS\Algorithm\PathFinding\AStarNode;
use phpOMS\Algorithm\PathFinding\Grid;
use phpOMS\Algorithm\PathFinding\HeuristicType;
use phpOMS\Algorithm\PathFinding\MovementType;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\PathFinding\AStarTest: AStar path finding
 *
 * @internal
 */
class AStarTest extends \PHPUnit\Framework\TestCase
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

    /**
     * @testdox The correct path is found for diagonal movement
     * @covers phpOMS\Algorithm\PathFinding\AStar
     * @group framework
     */
    public function testPathFindingDiagonal() : void
    {
        $grid = Grid::createGridFromArray($this->gridArray, AStarNode::class);
        $path = AStar::findPath(
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
            [0, 0, 3, 0, 9, 0, 0, 9, 9, 9, 9, 0, 0, 0, 0, ],
            [0, 3, 9, 9, 9, 0, 0, 3, 3, 0, 0, 0, 0, 0, 0, ],
            [0, 3, 0, 9, 0, 0, 3, 9, 0, 3, 9, 9, 9, 9, 0, ],
            [0, 3, 0, 9, 0, 3, 0, 9, 0, 0, 3, 3, 0, 0, 0, ],
            [0, 0, 3, 9, 3, 0, 0, 9, 0, 0, 9, 9, 3, 0, 0, ],
            [0, 0, 0, 3, 0, 9, 9, 9, 0, 0, 9, 3, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 9, 9, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
        ], $this->gridArray);
    }

    /**
     * @testdox The correct path is found for straight movement
     * @covers phpOMS\Algorithm\PathFinding\AStar
     * @group framework
     */
    public function testPathFindingStraight() : void
    {
        $grid = Grid::createGridFromArray($this->gridArray, AStarNode::class);
        $path = AStar::findPath(
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
            [0, 3, 0, 9, 0, 3, 0, 9, 3, 3, 9, 9, 9, 9, 0, ],
            [0, 3, 0, 9, 0, 3, 0, 9, 0, 3, 3, 3, 3, 0, 0, ],
            [0, 3, 0, 9, 3, 3, 0, 9, 0, 0, 9, 9, 3, 0, 0, ],
            [0, 3, 3, 3, 3, 9, 9, 9, 0, 0, 9, 3, 3, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 9, 9, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
        ], $this->gridArray);
    }

    /**
     * @testdox The correct path is found for diagonal movement [one obstacle]
     * @covers phpOMS\Algorithm\PathFinding\AStar
     * @group framework
     */
    public function testPathFindingDiagonalOneObstacle() : void
    {
        $grid = Grid::createGridFromArray($this->gridArray, AStarNode::class);
        $path = AStar::findPath(
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
            [0, 0, 3, 0, 9, 0, 0, 9, 9, 9, 9, 0, 0, 0, 0, ],
            [0, 3, 9, 9, 9, 0, 0, 3, 3, 0, 0, 0, 0, 0, 0, ],
            [0, 3, 0, 9, 0, 0, 3, 9, 0, 3, 9, 9, 9, 9, 0, ],
            [0, 3, 0, 9, 0, 3, 0, 9, 0, 0, 3, 3, 0, 0, 0, ],
            [0, 0, 3, 9, 3, 0, 0, 9, 0, 0, 9, 9, 3, 0, 0, ],
            [0, 0, 0, 3, 0, 9, 9, 9, 0, 0, 9, 3, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 9, 9, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
        ], $this->gridArray);
    }

    /**
     * @testdox The correct path is found for diagonal movement [no obstacle]
     * @covers phpOMS\Algorithm\PathFinding\AStar
     * @group framework
     */
    public function testPathFindingDiagonalNoObstacle() : void
    {
        $grid = Grid::createGridFromArray($this->gridArray, AStarNode::class);
        $path = AStar::findPath(
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

        self::assertEqualsWithDelta(24.07107, $path->getLength(), 0.001);

        self::assertEquals([
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 9, 9, 9, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 9, 9, 9, 9, 9, 0, 9, 0, 0, 0, 0, ],
            [0, 0, 3, 0, 9, 0, 0, 0, 0, 0, 9, 0, 9, 9, 9, ],
            [0, 3, 0, 0, 9, 0, 0, 9, 9, 9, 9, 0, 0, 0, 0, ],
            [0, 3, 9, 9, 9, 0, 3, 3, 3, 0, 0, 0, 0, 0, 0, ],
            [0, 3, 0, 9, 0, 3, 0, 9, 0, 3, 9, 9, 9, 9, 0, ],
            [0, 3, 0, 9, 3, 0, 0, 9, 0, 3, 3, 3, 3, 0, 0, ],
            [0, 3, 0, 9, 3, 0, 0, 9, 0, 0, 9, 9, 3, 0, 0, ],
            [0, 0, 3, 3, 3, 9, 9, 9, 0, 0, 9, 3, 3, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 9, 9, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
            [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, ],
        ], $this->gridArray);
    }
}
