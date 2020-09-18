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

namespace phpOMS\tests\Stdlib\Graph;

use phpOMS\Stdlib\Graph\Graph;
use phpOMS\Stdlib\Graph\Node;

/**
 * @testdox hpOMS\tests\Stdlib\Graph\Graph: Graph
 *
 * @internal
 */
class GraphTest extends \PHPUnit\Framework\TestCase
{
    protected Graph $graph;

    protected function setUp() : void
    {
        $this->graph = new Graph();
    }

    /**
     * @testdox The graph has the expected default values after initialization
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertNull($this->graph->getNode('invalid'));
        self::assertEquals([], $this->graph->getNodes());

        self::assertEquals(0, $this->graph->getDiameter());
        self::assertEquals(0, $this->graph->getOrder());
        self::assertEquals(0, $this->graph->getSize());
        self::assertEquals(0, $this->graph->getGirth());
        self::assertEquals(0, $this->graph->getCircuitRank());
        self::assertEquals(0, $this->graph->getNodeConnectivity());
        self::assertEquals(0, $this->graph->getEdgeConnectivity());

        self::assertTrue($this->graph->isConnected());
        self::assertTrue($this->graph->isBipartite());
        self::assertTrue($this->graph->isTriangleFree());
        self::assertFalse($this->graph->isDirected());
        self::assertFalse($this->graph->hasCycle());

        self::assertEquals([], $this->graph->getBridges());
        self::assertEquals([], $this->graph->getFloydWarshallShortestPath());
        self::assertEquals([], $this->graph->getDijkstraShortestPath());
        self::assertEquals([], $this->graph->depthFirstTraversal());
        self::assertEquals([], $this->graph->breadthFirstTraversal());
        self::assertEquals([], $this->graph->longestPath());
        self::assertEquals([], $this->graph->longestPathBetweenNodes('invalid1', 'invalid2'));
        self::assertEquals([], $this->graph->getUnconnected());

        self::assertEquals(0, $this->graph->getCost());
        self::assertEquals($this->graph, $this->graph->getKruskalMinimalSpanningTree());
    }

    /**
     * @testdox A graph can ge set as directed
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testDirectedInputOutput() : void
    {
        $this->graph->setDirected(true);
        self::assertTrue($this->graph->isDirected());
    }

    /**
     * @testdox A node can be add to a graph and returned
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testNodeInputOutput() : void
    {
        $node0 = new Node('0');
        $this->graph->setNode($node0);

        self::assertEquals($node0, $this->graph->getNode('0'));
    }

    /**
     * @testdox The existence of a node in a graph can be checked
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testNodeExists() : void
    {
        $node0 = new Node('0');
        $this->graph->setNode($node0);

        self::assertTrue($this->graph->hasNode('0'));
        self::assertFalse($this->graph->hasNode('1'));
    }

    /**
     * @testdox A graph can be checked for bridges
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testGraphWithBridge() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');
        $node6 = new Node('6');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);
        $this->graph->setNode($node4);
        $this->graph->setNode($node5);
        $this->graph->setNode($node6);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node1->setNodeRelative($node4);
        $node1->setNodeRelative($node6);
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        $bridges = $this->graph->getBridges();
        self::assertCount(1, $bridges);
        self::assertEquals('1', $bridges[0]->getNode1()->getId());
        self::assertEquals('6', $bridges[0]->getNode2()->getId());
    }

    /**
     * @testdox Multiple bridges are correctly identified in a graph
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testGraphWithBridges() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);

        $node0->setNodeRelative($node1);
        $node1->setNodeRelative($node2);
        $node2->setNodeRelative($node3);

        $bridges = $this->graph->getBridges();
        self::assertCount(3, $bridges);
    }

    /**
     * @testdox A graph without bridges is correctly classified
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testGraphWithoutBridges() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);

        $bridges = $this->graph->getBridges();
        self::assertCount(0, $bridges);
    }

    /**
     * @testdox Edges can be add and returned from a graph
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testEdgesInputOutput() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');
        $node6 = new Node('6');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);
        $this->graph->setNode($node4);
        $this->graph->setNode($node5);
        $this->graph->setNode($node6);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node1->setNodeRelative($node4);
        $node1->setNodeRelative($node6);
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        $edges = $this->graph->getEdges();
        self::assertCount(8, $edges);
    }

    /**
     * @testdox An edge can be found by two edge ids
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testEdgeInputOutput() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');
        $node6 = new Node('6');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);
        $this->graph->setNode($node4);
        $this->graph->setNode($node5);
        $this->graph->setNode($node6);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node1->setNodeRelative($node4);
        $node1->setNodeRelative($node6);
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        self::assertEquals(
            $node1->getId(),
            $this->graph->getEdge($node1->getId(), $node3->getId())->getNode1()->getId()
        );

        self::assertEquals(
            $node3->getId(),
            $this->graph->getEdge($node1->getId(), $node3->getId())->getNode2()->getId()
        );

        self::assertEquals(null, $this->graph->getEdge('invalid1', 'invalid2'));
    }

    /**
     * @testdox The existence of cycles in undirected graphs can be checked
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testUndirectedCycle() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);

        $node0->setNodeRelative($node1);
        $node1->setNodeRelative($node2);
        $node2->setNodeRelative($node3);

        self::assertFalse($this->graph->hasCycle());

        $node3->setNodeRelative($node1);
        self::assertTrue($this->graph->hasCycle());
    }

    /**
     * @testdox The existence of cycles in directed graphs can be checked
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testDirectedCycle() : void
    {
        $this->graph->setDirected(true);

        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);

        $this->graph->setNodeRelative($node0, $node1);
        $this->graph->setNodeRelative($node1, $node2);
        $this->graph->setNodeRelative($node2, $node3);
        $this->graph->setNodeRelative($node1, $node3);

        self::assertFalse($this->graph->hasCycle());

        $node3->setNodeRelative($node1);
        self::assertTrue($this->graph->hasCycle());
    }

    /**
     * @testdox The cost of a graph can be calculated
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testCost() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);

        $node0->setNodeRelative($node1)->setWeight(2.0);
        $node2->setNodeRelative($node3)->setWeight(3.0);

        self::assertEquals(5.0, $this->graph->getCost());
    }

    /**
     * @testdox The Kruskal minimal spanning tree can be created
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testKruskalMinimalSpanningTree() : void
    {
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');
        $node6 = new Node('6');

        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);
        $this->graph->setNode($node4);
        $this->graph->setNode($node5);
        $this->graph->setNode($node6);

        $node1->setNodeRelative($node5)->setWeight(4.0);
        $node1->setNodeRelative($node4)->setWeight(1.0);
        $node1->setNodeRelative($node2)->setWeight(2.0);

        $node2->setNodeRelative($node3)->setWeight(3.0);
        $node2->setNodeRelative($node4)->setWeight(3.0);
        $node2->setNodeRelative($node6)->setWeight(7.0);

        $node3->setNodeRelative($node4)->setWeight(5.0);
        $node3->setNodeRelative($node6)->setWeight(8.0);

        $node4->setNodeRelative($node5)->setWeight(9.0);

        $minimalSpanningTree = $this->graph->getKruskalMinimalSpanningTree();
        $nodes               = $minimalSpanningTree->getNodes();

        self::assertCount(6, $nodes);
        self::assertEquals(17.0, $minimalSpanningTree->getCost());
    }
}
