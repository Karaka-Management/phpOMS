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

namespace phpOMS\tests\Stdlib\Graph;

use phpOMS\Stdlib\Graph\Edge;
use phpOMS\Stdlib\Graph\Node;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Graph\Node::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Stdlib\Graph\NodeTest: Node in a graph')]
final class NodeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The node has the expected default values after initialization')]
    public function testDefault() : void
    {
        $node = new Node('A');
        self::assertEquals('A', $node->getId());
        self::assertNull($node->getData());
        self::assertNull($node->getEdge(0));
        self::assertEquals([], $node->getEdges());
        self::assertEquals([], $node->getNeighbors());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The node data can be set and returned')]
    public function testDataInputOutput() : void
    {
        $node = new Node('B', 1);
        self::assertEquals(1, $node->getData());

        $node->setData(false);
        self::assertFalse($node->getData());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Two equal nodes are equal')]
    public function testNodesEqual() : void
    {
        $node = new Node('B', 1);
        self::assertTrue($node->isEqual($node));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Two different nodes are not equal')]
    public function testNodesNotEqual() : void
    {
        $node  = new Node('A', 1);
        $node2 = new Node('B', 1);

        self::assertFalse($node->isEqual($node2));

        $node  = new Node('A', 1);
        $node2 = new Node('A', 2);

        self::assertFalse($node->isEqual($node2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An edge for a node can be defined')]
    public function testEdgeInputOutput() : void
    {
        $node = new Node('A', 1);
        $node->setEdge(new Edge($node, new Node('B')));

        self::assertCount(1, $node->getEdges());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Edges can be removed from a node')]
    public function testEdgeRemove() : void
    {
        $node = new Node('A', 1);
        $node->setEdge(new Edge($node, new Node('B')));
        $node->removeEdges();

        self::assertCount(0, $node->getEdges());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An edge for a node can be defined by key')]
    public function testEdgeKeyInputOutput() : void
    {
        $node = new Node('A', 1);
        $node->setEdge(new Edge($node, new Node('B')), 3);

        self::assertNull($node->getEdge(2));
        self::assertInstanceOf(Edge::class, $node->getEdge(3));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A node relationship can be defined')]
    public function testNodeRelation() : void
    {
        $node1 = new Node('A');
        $node2 = new Node('B');

        self::assertInstanceOf(Edge::class, $edge = $node1->setNodeRelative($node2, null, false));
        self::assertCount(1, $node2->getEdges());
        self::assertFalse($edge->isDirected);
    }

    /**
     * @bug Directed graphs may return invalid neighbors
     *      https://github.com/Karaka-Management/phpOMS/issues/366
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All neighbors of a node can be returned')]
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

    public function testFindEdgeFromNeighbor() : void
    {
        $node1 = new Node('A');
        $node2 = new Node('B');
        $node3 = new Node('C');
        $node4 = new Node('D');

        $node3->setNodeRelative($node4);

        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);

        self::assertEquals('C', $node1->getEdgeByNeighbor($node3)->node2->getId());
        self::assertNull($node1->getEdgeByNeighbor($node4));
    }
}
