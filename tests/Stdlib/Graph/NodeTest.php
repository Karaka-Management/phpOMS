<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Graph;

use phpOMS\Stdlib\Graph\Edge;
use phpOMS\Stdlib\Graph\Node;

/**
 * @testdox phpOMS\tests\Stdlib\Graph\NodeTest: Node in a graph
 *
 * @internal
 */
class NodeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The node has the expected default values after initialization
     * @covers phpOMS\Stdlib\Graph\Node
     * @group framework
     */
    public function testDefault() : void
    {
        $node = new Node('A');
        self::assertEquals('A', $node->getId());
        self::assertNull($node->getData());
        self::assertNull($node->getEdge(0));
        self::assertEquals([], $node->getEdges());
        self::assertEquals([], $node->getNeighbors());
    }

    /**
     * @testdox The node data can be set and returned
     * @covers phpOMS\Stdlib\Graph\Node
     * @group framework
     */
    public function testDataInputOutput() : void
    {
        $node = new Node('B', 1);
        self::assertEquals(1, $node->getData());

        $node->setData(false);
        self::assertFalse($node->getData());
    }

    /**
     * @testdox Two equal nodes are equal
     * @covers phpOMS\Stdlib\Graph\Node
     * @group framework
     */
    public function testNodesEqual() : void
    {
        $node = new Node('B', 1);
        self::assertTrue($node->isEqual($node));
    }

    /**
     * @testdox Two different nodes are not equal
     * @covers phpOMS\Stdlib\Graph\Node
     * @group framework
     */
    public function testNodesNotEqual() : void
    {
        $node  = new Node('A', 1);
        $node2 = new Node('B', 1);

        self::assertFalse($node->isEqual($node2));

        $node  = new Node('A', 1);
        $node2 = new Node('A', 2);

        self::assertFalse($node->isEqual($node2));
    }

    /**
     * @testdox An edge for a node can be defined
     * @covers phpOMS\Stdlib\Graph\Node
     * @group framework
     */
    public function testEdgeInputOutput() : void
    {
        $node = new Node('A', 1);
        $node->setEdge(new Edge($node, new Node('B')));

        self::assertCount(1, $node->getEdges());
    }

    /**
     * @testdox Edges can be removed from a node
     * @covers phpOMS\Stdlib\Graph\Node
     * @group framework
     */
    public function testEdgeRemove() : void
    {
        $node = new Node('A', 1);
        $node->setEdge(new Edge($node, new Node('B')));
        $node->removeEdges();

        self::assertCount(0, $node->getEdges());
    }

    /**
     * @testdox An edge for a node can be defined by key
     * @covers phpOMS\Stdlib\Graph\Node
     * @group framework
     */
    public function testEdgeKeyInputOutput() : void
    {
        $node = new Node('A', 1);
        $node->setEdge(new Edge($node, new Node('B')), 3);

        self::assertNull($node->getEdge(2));
        self::assertInstanceOf(Edge::class, $node->getEdge(3));
    }

    /**
     * @testdox A node relationship can be defined
     * @covers phpOMS\Stdlib\Graph\Node
     * @group framework
     */
    public function testNodeRelation() : void
    {
        $node1 = new Node('A');
        $node2 = new Node('B');

        self::assertInstanceOf(Edge::class, $edge = $node1->setNodeRelative($node2, null, false));
        self::assertCount(1, $node2->getEdges());
        self::assertFalse($edge->isDirected());
    }

    /**
     * @testdox All neighbors of a node can be returned
     * @covers phpOMS\Stdlib\Graph\Node
     * @group framework
     *
     * @todo: is there bug where directed graphs return invalid neighbors?
     */
    public function testNeighborsInputOutput() : void
    {
        $node1 = new Node('A');
        $node2 = new Node('B');
        $node3 = new Node('C');
        $node4 = new Node('D');

        $node3->setNodeRelative($node4);

        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);

        self::assertCount(2, $node1->getNeighbors());
        self::assertCount(1, $node4->getNeighbors());
    }
}
