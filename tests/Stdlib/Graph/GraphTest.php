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

include_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Stdlib\Graph\Graph;
use phpOMS\Stdlib\Graph\Node;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Graph\Graph::class)]
#[\PHPUnit\Framework\Attributes\TestDox('hpOMS\tests\Stdlib\Graph\Graph: Graph')]
final class GraphTest extends \PHPUnit\Framework\TestCase
{
    protected Graph $graph;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->graph = new Graph();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The graph has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertNull($this->graph->getNode('invalid'));
        self::assertEquals([], $this->graph->getNodes());

        self::assertEquals(0, $this->graph->getDiameter());
        self::assertEquals(0, $this->graph->getOrder());
        self::assertEquals(0, $this->graph->getSize());
        self::assertEquals(\PHP_INT_MAX, $this->graph->getGirth());
        self::assertEquals(0, $this->graph->getCircuitRank());

        self::assertTrue($this->graph->isConnected());
        self::assertTrue($this->graph->isBipartite());
        self::assertFalse($this->graph->isDirected());
        self::assertFalse($this->graph->hasCycle());

        self::assertEquals([], $this->graph->getBridges());
        self::assertEquals([], $this->graph->getFloydWarshallShortestPath());
        self::assertEquals([], $this->graph->longestPath());
        self::assertEquals([], $this->graph->longestPathBetweenNodes('invalid1', 'invalid2'));
        self::assertEquals([], $this->graph->shortestPathBetweenNodes('invalid1', 'invalid2'));
        self::assertEquals([], $this->graph->getAllPathsBetweenNodes('invalid1', 'invalid2'));
        self::assertEquals([], $this->graph->findAllReachableNodesDFS('invalid1'));

        self::assertEquals(0, $this->graph->getCost());
        self::assertEquals($this->graph, $this->graph->getKruskalMinimalSpanningTree());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A graph can ge set as directed')]
    public function testDirectedOutput() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);

        $node0->setNodeRelative($node1, isDirected: true);

        $this->graph->isDirected = false;
        self::assertTrue($this->graph->isDirected());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A node can be add to a graph and returned')]
    public function testNodeInputOutput() : void
    {
        $node0 = new Node('0');
        $this->graph->setNode($node0);

        self::assertEquals($node0, $this->graph->getNode('0'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The existence of a node in a graph can be checked')]
    public function testNodeExists() : void
    {
        $node0 = new Node('0');
        $this->graph->setNode($node0);

        self::assertTrue($this->graph->hasNode('0'));
        self::assertFalse($this->graph->hasNode('1'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A graph can be checked for bridges')]
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
        self::assertEquals('1', $bridges[0]->node1->getId());
        self::assertEquals('6', $bridges[0]->node2->getId());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Multiple bridges are correctly identified in a graph')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A graph without bridges is correctly classified')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Edges can be add and returned from a graph')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An edge can be found by two edge ids')]
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
            $this->graph->getEdge($node1->getId(), $node3->getId())->node1->getId()
        );

        self::assertEquals(
            $node3->getId(),
            $this->graph->getEdge($node1->getId(), $node3->getId())->node2->getId()
        );

        self::assertNull($this->graph->getEdge('invalid1', 'invalid2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The existence of cycles in undirected graphs can be checked')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The existence of cycles in directed graphs can be checked')]
    public function testDirectedCycle() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);

        $this->graph->setNodeRelative($node0, $node1, true);
        $this->graph->setNodeRelative($node1, $node2, true);
        $this->graph->setNodeRelative($node2, $node3, true);
        $this->graph->setNodeRelative($node1, $node3, true);

        $this->graph->isDirected = true;

        self::assertFalse($this->graph->hasCycle());

        $node3->setNodeRelative($node1);
        self::assertTrue($this->graph->hasCycle());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The cost of a graph can be calculated')]
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

        $node0->setNodeRelative($node1)->weight = 2.0;
        $node2->setNodeRelative($node3)->weight = 3.0;

        self::assertEquals(5.0, $this->graph->getCost());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The Kruskal minimal spanning tree can be created')]
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

        $node1->setNodeRelative($node5)->weight = 4.0;
        $node1->setNodeRelative($node4)->weight = 1.0;
        $node1->setNodeRelative($node2)->weight = 2.0;

        $node2->setNodeRelative($node3)->weight = 3.0;
        $node2->setNodeRelative($node4)->weight = 3.0;
        $node2->setNodeRelative($node6)->weight = 7.0;

        $node3->setNodeRelative($node4)->weight = 5.0;
        $node3->setNodeRelative($node6)->weight = 8.0;

        $node4->setNodeRelative($node5)->weight = 9.0;

        $minimalSpanningTree = $this->graph->getKruskalMinimalSpanningTree();
        $nodes               = $minimalSpanningTree->getNodes();

        self::assertCount(6, $nodes);
        self::assertEquals(17.0, $minimalSpanningTree->getCost());
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4   6
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testFindAllReachableNodesDFS() : void
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
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        $nodes = $this->graph->findAllReachableNodesDFS($node0);
        self::assertCount(6, $nodes);
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4   6
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testGetAllPathsBetweenNodes() : void
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
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        $paths = $this->graph->getAllPathsBetweenNodes($node0, $node5);
        self::assertCount(4, $paths);
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4   6
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCountAllPathsBetweenNodes() : void
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
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        self::assertEquals(4, $this->graph->countAllPathsBetweenNodes($node0, $node5));
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4   6
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testLongestPathBetweenNodes() : void
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
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        $path = $this->graph->longestPathBetweenNodes($node0, $node5);
        self::assertCount(4, $path);

        $path = $this->graph->longestPathBetweenNodes($node0, $node6);
        self::assertEquals([], $path);
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4   6
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testShortestPathBetweenNodes() : void
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
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        $path = $this->graph->shortestPathBetweenNodes($node0, $node5);
        self::assertCount(3, $path);
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4   6
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testShortestPathFloydWarshall() : void
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
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        $paths = $this->graph->getFloydWarshallShortestPath();
        self::assertGreaterThan(1, $paths);
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4   6
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testLongestPathsDfs() : void
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
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        $paths = $this->graph->longestPath();
        self::assertGreaterThan(1, $paths);
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4   6
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testUnconnectedGraph() : void
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
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        self::assertFalse($this->graph->isConnected());
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testConnectedGraph() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);
        $this->graph->setNode($node4);
        $this->graph->setNode($node5);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node1->setNodeRelative($node4);
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        self::assertTrue($this->graph->isConnected());
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDiameter() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);
        $this->graph->setNode($node4);
        $this->graph->setNode($node5);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node1->setNodeRelative($node4);
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        self::assertGreaterThan(3, $this->graph->getDiameter());
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testGirth() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);
        $this->graph->setNode($node4);
        $this->graph->setNode($node5);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node1->setNodeRelative($node4);
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        self::assertEquals(3, $this->graph->getGirth());
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCircuitRank() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);
        $this->graph->setNode($node4);
        $this->graph->setNode($node5);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node1->setNodeRelative($node4);
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        self::assertGreaterThan(2, $this->graph->getCircuitRank());
    }

    /**
     *     1
     *   / | \
     * 0---|---3
     *   \ | /
     *     2
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStronglyConnected() : void
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
        $node0->setNodeRelative($node2);
        $node0->setNodeRelative($node3);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node2->setNodeRelative($node3);

        self::assertTrue($this->graph->isStronglyConnected());
    }

    /**
     *     0 - 1 - 2
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidStronglyConnected() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);

        $node0->setNodeRelative($node1);
        $node1->setNodeRelative($node2);

        self::assertFalse($this->graph->isStronglyConnected());
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testBipartite() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);
        $this->graph->setNode($node4);
        $this->graph->setNode($node5);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node1->setNodeRelative($node4);
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        self::assertTrue($this->graph->isBipartite());
    }

    /**
     *     1 - 3 - 5
     *   / |\     /
     * 0   | \   /
     *   \ |  \ /
     *     2   4
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testTriangles() : void
    {
        $node0 = new Node('0');
        $node1 = new Node('1');
        $node2 = new Node('2');
        $node3 = new Node('3');
        $node4 = new Node('4');
        $node5 = new Node('5');

        $this->graph->setNode($node0);
        $this->graph->setNode($node1);
        $this->graph->setNode($node2);
        $this->graph->setNode($node3);
        $this->graph->setNode($node4);
        $this->graph->setNode($node5);

        $node0->setNodeRelative($node1);
        $node0->setNodeRelative($node2);
        $node1->setNodeRelative($node2);
        $node1->setNodeRelative($node3);
        $node1->setNodeRelative($node4);
        $node3->setNodeRelative($node5);
        $node4->setNodeRelative($node5);

        self::assertTrue($this->graph->hasTriangles());
    }
}
