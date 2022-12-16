<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\PathFinding;

use phpOMS\Algorithm\PathFinding\Grid;
use phpOMS\Algorithm\PathFinding\Node;
use phpOMS\Algorithm\PathFinding\Path;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\PathFinding\PathTest: Path on grid
 *
 * @internal
 */
final class PathTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The path has the expected values after initialization
     * @covers phpOMS\Algorithm\PathFinding\Path
     * @group framework
     */
    public function testDefault() : void
    {
        $path = new Path(new Grid());
        self::assertEquals(0, $path->getLength());
        self::assertEquals([], $path->getPath());
        self::assertEquals([], $path->expandPath());
    }

    /**
     * @testdox The diagonal euclidean path length is calculated correctly
     * @covers phpOMS\Algorithm\PathFinding\Path
     * @group framework
     */
    public function testDiagonalPathLength() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
        ], Node::class);

        $path = new Path($grid);

        $path->addNode(new Node(1, 3));
        $path->addNode(new Node(3, 1));
        $path->addNode(new Node(4, 0));

        self::assertEqualsWithDelta(4.2426, $path->getLength(), 0.001);
    }

    /**
     * @testdox The straight euclidean path length is calculated correctly
     * @covers phpOMS\Algorithm\PathFinding\Path
     * @group framework
     */
    public function testStraightPathLength() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
        ], Node::class);

        $path = new Path($grid);

        $path->addNode(new Node(1, 3));
        $path->addNode(new Node(1, 1));
        $path->addNode(new Node(3, 1));

        self::assertEqualsWithDelta(4.0, $path->getLength(), 0.001);
    }

    /**
     * @testdox The path is correctly expanded in case only jump points are defined
     * @covers phpOMS\Algorithm\PathFinding\Path
     * @group framework
     */
    public function testPathExpansion() : void
    {
        $grid = Grid::createGridFromArray([
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
        ], Node::class);

        $path = new Path($grid);

        $path->addNode(new Node(1, 3));
        $path->addNode(new Node(3, 1));
        $path->addNode(new Node(4, 0));

        self::assertEquals(3, \count($path->getPath()));
        self::assertEquals(4, \count($path->expandPath()));
    }
}
