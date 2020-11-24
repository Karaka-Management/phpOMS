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

use phpOMS\Algorithm\PathFinding\Node;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\PathFinding\NodeTest: Node on grid for path finding
 *
 * @internal
 */
class NodeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The node has the expected values after initialization
     * @covers phpOMS\Algorithm\PathFinding\Node
     * @group framework
     */
    public function testDefault() : void
    {
        $node = new Node(1, 2, 3.0, false);

        self::assertEquals(1, $node->getX());
        self::assertEquals(2, $node->getY());
        self::assertEquals(['x' => 1, 'y' => 2], $node->getCoordinates());
        self::assertEquals(3.0, $node->getWeight());
        self::assertNull($node->parent);
        self::assertFalse($node->isWalkable);
    }

    /**
     * @testdox Nodes with equal coordinates are equal
     * @covers phpOMS\Algorithm\PathFinding\Node
     * @group framework
     */
    public function testNodesWithEqualCoordinatesAreEqual() : void
    {
        $node  = new Node(1, 2, 3.0, false);
        $node2 = new Node(1, 2, 2.0, true);

        self::assertTrue($node->isEqual($node2));
    }

    /**
     * @testdox Nodes with different coordinates are not equal
     * @covers phpOMS\Algorithm\PathFinding\Node
     * @group framework
     */
    public function testNodesWithDifferentCoordinatesAreNotEqual() : void
    {
        $node  = new Node(1, 2, 3.0, false);
        $node2 = new Node(2, 2, 3.0, false);

        self::assertFalse($node->isEqual($node2));
    }

    /**
     * @testdox A parent node can be set and returned
     * @covers phpOMS\Algorithm\PathFinding\Node
     * @group framework
     */
    public function testParentInputOutput() : void
    {
        $node  = new Node(1, 2, 3.0, false);
        $node2 = new Node(2, 2, 3.0, false);

        $node->parent = $node2;
        self::assertTrue($node2->isEqual($node->parent));
    }
}
