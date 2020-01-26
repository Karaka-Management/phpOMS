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
    /**
     * @testdox The graph has the expected default values after initialization
     * @covers phpOMS\Stdlib\Graph\Graph
     * @group framework
     */
    public function testDefault() : void
    {
        $graph = new Graph();

        self::assertNull($graph->getNode('invalid'));
        self::assertEquals([], $graph->getNodes());

        self::assertEquals(0, $graph->getDiameter());
        self::assertEquals(0, $graph->getOrder());
        self::assertEquals(0, $graph->getSize());
        self::assertEquals(0, $graph->getGirth());
        self::assertEquals(0, $graph->getCircuitRank());
        self::assertEquals(0, $graph->getNodeConnectivity());
        self::assertEquals(0, $graph->getEdgeConnectivity());

        self::assertTrue($graph->isConnected());
        self::assertTrue($graph->isBipartite());
        self::assertTrue($graph->isTriangleFree());

        self::assertEquals([], $graph->getBridges());
        self::assertEquals([], $graph->getFloydWarshallShortestPath());
        self::assertEquals([], $graph->getDijkstraShortestPath());
        self::assertEquals([], $graph->depthFirstTraversal());
        self::assertEquals([], $graph->breadthFirstTraversal());
        self::assertEquals([], $graph->longestPath());
        self::assertEquals([], $graph->longestPathBetweenNodes('invalid1', 'invalid2'));
        self::assertEquals([], $graph->getUnconnected());
    }

    public function testGraphWithBridge() : void
    {
        $graph = new Graph();

        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');
        $node6 = new Node('6');

        $graph->setNode($node0);
        $graph->setNode($node1);
        $graph->setNode($node2);
        $graph->setNode($node3);
        $graph->setNode($node4);
        $graph->setNode($node5);
        $graph->setNode($node6);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node1->setNodeRelative($node4);
        $node1->setNodeRelative($node6);
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        $bridges = $graph->getBridges();
        self::assertCount(1, $bridges);
        self::assertEquals('1', $bridges[0]->getNode1()->getId());
        self::assertEquals('6', $bridges[0]->getNode2()->getId());
    }

    public function testGraphWithBridges() : void
    {
        $graph = new Graph();

        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');

        $graph->setNode($node0);
        $graph->setNode($node1);
        $graph->setNode($node2);
        $graph->setNode($node3);

        $node0->setNodeRelative($node1);
        $node1->setNodeRelative($node2);
        $node2->setNodeRelative($node3);

        $bridges = $graph->getBridges();
        self::assertCount(3, $bridges);
    }

    public function testGraphWithoutBridges() : void
    {
        $graph = new Graph();

        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');

        $graph->setNode($node0);
        $graph->setNode($node1);
        $graph->setNode($node2);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);

        $bridges = $graph->getBridges();
        self::assertCount(0, $bridges);
    }

    public function testEdgesInputOutput() : void
    {
        $graph = new Graph();

        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');
        $node6 = new Node('6');

        $graph->setNode($node0);
        $graph->setNode($node1);
        $graph->setNode($node2);
        $graph->setNode($node3);
        $graph->setNode($node4);
        $graph->setNode($node5);
        $graph->setNode($node6);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node1->setNodeRelative($node4);
        $node1->setNodeRelative($node6);
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        $edges = $graph->getEdges();
        self::assertCount(8, $edges);
    }
}
