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
use phpOMS\Algorithm\PathFinding\MovementType;
use phpOMS\Algorithm\PathFinding\Node;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\PathFinding\Grid::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\PathFinding\GridTest: Grid for path finding')]
final class GridTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('By default a grid is empty')]
    public function testDefault() : void
    {
        $grid = new Grid();
        self::assertNull($grid->getNode(0, 0));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A grid can be created from an array')]
    public function testGridFromArray() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 9, 0],
            [0, 9, 0],
            [0, 9, 0],
        ], Node::class);

        self::assertTrue($grid->isWalkable(0, 0));
        self::assertFalse($grid->isWalkable(1, 0));
        self::assertTrue($grid->isWalkable(2, 0));

        self::assertTrue($grid->isWalkable(0, 1));
        self::assertFalse($grid->isWalkable(1, 1));
        self::assertTrue($grid->isWalkable(2, 1));

        self::assertTrue($grid->isWalkable(0, 2));
        self::assertFalse($grid->isWalkable(1, 2));
        self::assertTrue($grid->isWalkable(2, 2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A node can be set and returned from the grid')]
    public function testNodeInputOutput() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 9, 0],
            [0, 9, 0],
            [0, 9, 0],
        ], Node::class);

        $grid->setNode(0, 0, new Node(0, 0, 1.0, false));
        self::assertFalse($grid->getNode(0, 0)->isWalkable);
        self::assertFalse($grid->isWalkable(0, 0));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Out of bounds nodes cannot be returned')]
    public function testOutOfBoundsNode() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 9, 0],
            [0, 9, 0],
            [0, 9, 0],
        ], Node::class);

        self::assertNull($grid->getNode(-1, 0));
        self::assertNull($grid->getNode(0, -1));
        self::assertNull($grid->getNode(3, 0));
        self::assertNull($grid->getNode(0, 3));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All horizontal neighbors can be found correctly')]
    public function testStraightHorizontalNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 9, 0],
            [0, 0, 0],
            [0, 9, 0],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::STRAIGHT);

        self::assertEquals(2, \count($neighbors));
        self::assertTrue($grid->getNode(0, 1)->isEqual($neighbors[1]));
        self::assertTrue($grid->getNode(2, 1)->isEqual($neighbors[0]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All vertical neighbors can be found correctly')]
    public function testStraightVerticalNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 0, 0],
            [9, 0, 9],
            [0, 0, 0],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::STRAIGHT);

        self::assertEquals(2, \count($neighbors));
        self::assertTrue($grid->getNode(1, 0)->isEqual($neighbors[0]));
        self::assertTrue($grid->getNode(1, 2)->isEqual($neighbors[1]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('No straight neighbors are found if no straight neighbors exist')]
    public function testStraightNoneNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 9, 0],
            [9, 0, 9],
            [0, 9, 0],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::STRAIGHT);

        self::assertEquals(0, \count($neighbors));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All straight neighbors can be found correctly')]
    public function testStraightAllNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [9, 0, 9],
            [0, 0, 0],
            [9, 0, 9],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::STRAIGHT);

        self::assertEquals(4, \count($neighbors));
        self::assertTrue($grid->getNode(0, 1)->isEqual($neighbors[3]));
        self::assertTrue($grid->getNode(2, 1)->isEqual($neighbors[1]));
        self::assertTrue($grid->getNode(1, 0)->isEqual($neighbors[0]));
        self::assertTrue($grid->getNode(1, 2)->isEqual($neighbors[2]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All neighbors except blocked diagonal neighbors can be found correctly')]
    public function testDiagonalLRNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 0, 9],
            [0, 0, 0],
            [9, 0, 0],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::DIAGONAL);

        self::assertEquals(6, \count($neighbors));
        self::assertTrue($grid->getNode(0, 0)->isEqual($neighbors[4]));
        self::assertTrue($grid->getNode(1, 0)->isEqual($neighbors[0]));
        self::assertTrue($grid->getNode(0, 1)->isEqual($neighbors[3]));
        self::assertTrue($grid->getNode(2, 1)->isEqual($neighbors[1]));
        self::assertTrue($grid->getNode(1, 2)->isEqual($neighbors[2]));
        self::assertTrue($grid->getNode(2, 2)->isEqual($neighbors[5]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All neighbors except blocked diagonal neighbors can be found correctly')]
    public function testDiagonalURNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [9, 0, 0],
            [0, 0, 0],
            [0, 0, 9],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::DIAGONAL);

        self::assertEquals(6, \count($neighbors));
        self::assertTrue($grid->getNode(1, 0)->isEqual($neighbors[0]));
        self::assertTrue($grid->getNode(2, 0)->isEqual($neighbors[4]));
        self::assertTrue($grid->getNode(0, 1)->isEqual($neighbors[3]));
        self::assertTrue($grid->getNode(2, 1)->isEqual($neighbors[1]));
        self::assertTrue($grid->getNode(1, 2)->isEqual($neighbors[2]));
        self::assertTrue($grid->getNode(0, 2)->isEqual($neighbors[5]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('No diagonal neighbors are found if no neighbors exist')]
    public function testDiagonalNoneNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [9, 9, 9],
            [9, 0, 9],
            [9, 9, 9],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::DIAGONAL);

        self::assertEquals(0, \count($neighbors));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All diagonal neighbors can be found correctly')]
    public function testDiagonalOnlyNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 9, 0],
            [9, 0, 9],
            [0, 9, 0],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::DIAGONAL);

        self::assertEquals(4, \count($neighbors));
        self::assertTrue($grid->getNode(0, 0)->isEqual($neighbors[0]));
        self::assertTrue($grid->getNode(2, 0)->isEqual($neighbors[1]));
        self::assertTrue($grid->getNode(0, 2)->isEqual($neighbors[3]));
        self::assertTrue($grid->getNode(2, 2)->isEqual($neighbors[2]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All neighbors can be found correctly')]
    public function testDiagonalAllNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::DIAGONAL);

        self::assertEquals(8, \count($neighbors));
        self::assertTrue($grid->getNode(0, 0)->isEqual($neighbors[4]));
        self::assertTrue($grid->getNode(1, 0)->isEqual($neighbors[0]));
        self::assertTrue($grid->getNode(2, 0)->isEqual($neighbors[5]));
        self::assertTrue($grid->getNode(0, 1)->isEqual($neighbors[3]));
        self::assertTrue($grid->getNode(2, 1)->isEqual($neighbors[1]));
        self::assertTrue($grid->getNode(0, 2)->isEqual($neighbors[7]));
        self::assertTrue($grid->getNode(1, 2)->isEqual($neighbors[2]));
        self::assertTrue($grid->getNode(2, 2)->isEqual($neighbors[6]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All neighbors can be found correctly even if one obstacle exists')]
    public function testDiagonalOneObstacleNoBlockNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 9, 0],
            [0, 0, 0],
            [0, 0, 0],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::DIAGONAL_ONE_OBSTACLE);

        self::assertEquals(7, \count($neighbors));
        self::assertTrue($grid->getNode(0, 0)->isEqual($neighbors[3]));
        self::assertTrue($grid->getNode(2, 0)->isEqual($neighbors[4]));
        self::assertTrue($grid->getNode(0, 1)->isEqual($neighbors[2]));
        self::assertTrue($grid->getNode(2, 1)->isEqual($neighbors[0]));
        self::assertTrue($grid->getNode(0, 2)->isEqual($neighbors[6]));
        self::assertTrue($grid->getNode(1, 2)->isEqual($neighbors[1]));
        self::assertTrue($grid->getNode(2, 2)->isEqual($neighbors[5]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('No diagonal neighbors are found if they are blocked on two sides')]
    public function testDiagonalOneObstacleBlockNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 9, 0],
            [0, 0, 9],
            [0, 0, 0],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::DIAGONAL_ONE_OBSTACLE);

        self::assertEquals(5, \count($neighbors));
        self::assertTrue($grid->getNode(0, 0)->isEqual($neighbors[2]));
        self::assertTrue($grid->getNode(0, 1)->isEqual($neighbors[1]));
        self::assertTrue($grid->getNode(0, 2)->isEqual($neighbors[4]));
        self::assertTrue($grid->getNode(1, 2)->isEqual($neighbors[0]));
        self::assertTrue($grid->getNode(2, 2)->isEqual($neighbors[3]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All neighbors can be found correctly if no obstacles exists')]
    public function testDiagonalOneObstacleAllNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::DIAGONAL_ONE_OBSTACLE);

        self::assertEquals(8, \count($neighbors));
        self::assertTrue($grid->getNode(0, 0)->isEqual($neighbors[4]));
        self::assertTrue($grid->getNode(1, 0)->isEqual($neighbors[0]));
        self::assertTrue($grid->getNode(2, 0)->isEqual($neighbors[5]));
        self::assertTrue($grid->getNode(0, 1)->isEqual($neighbors[3]));
        self::assertTrue($grid->getNode(2, 1)->isEqual($neighbors[1]));
        self::assertTrue($grid->getNode(0, 2)->isEqual($neighbors[7]));
        self::assertTrue($grid->getNode(1, 2)->isEqual($neighbors[2]));
        self::assertTrue($grid->getNode(2, 2)->isEqual($neighbors[6]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('No diagonal neighbors are found if one obstacle exists')]
    public function testDiagonalNoObstacleBlockNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 9, 0],
            [0, 0, 0],
            [0, 0, 0],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::DIAGONAL_NO_OBSTACLE);

        self::assertEquals(5, \count($neighbors));
        self::assertTrue($grid->getNode(0, 1)->isEqual($neighbors[2]));
        self::assertTrue($grid->getNode(2, 1)->isEqual($neighbors[0]));
        self::assertTrue($grid->getNode(0, 2)->isEqual($neighbors[4]));
        self::assertTrue($grid->getNode(1, 2)->isEqual($neighbors[1]));
        self::assertTrue($grid->getNode(2, 2)->isEqual($neighbors[3]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All neighbors can be found correctly if no obstacles exist')]
    public function testDiagonalNoObstacleAllNeighbors() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ], Node::class);

        $node      = $grid->getNode(1, 1);
        $neighbors = $grid->getNeighbors($node, MovementType::DIAGONAL_NO_OBSTACLE);

        self::assertEquals(8, \count($neighbors));
        self::assertTrue($grid->getNode(0, 0)->isEqual($neighbors[4]));
        self::assertTrue($grid->getNode(1, 0)->isEqual($neighbors[0]));
        self::assertTrue($grid->getNode(2, 0)->isEqual($neighbors[5]));
        self::assertTrue($grid->getNode(0, 1)->isEqual($neighbors[3]));
        self::assertTrue($grid->getNode(2, 1)->isEqual($neighbors[1]));
        self::assertTrue($grid->getNode(0, 2)->isEqual($neighbors[7]));
        self::assertTrue($grid->getNode(1, 2)->isEqual($neighbors[2]));
        self::assertTrue($grid->getNode(2, 2)->isEqual($neighbors[6]));
    }
}
