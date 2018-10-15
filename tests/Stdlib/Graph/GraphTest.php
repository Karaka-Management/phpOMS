<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Stdlib\Graph;

use phpOMS\Stdlib\Graph\Graph;

class GraphTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $graph = new Graph();

        self::assertEquals(null, $graph->getNode('invalid'));
        self::assertEquals([], $graph->getNodes());

        self::assertEquals(null, $graph->getEdge('invalid'));
        self::assertEquals([], $graph->getEdges());

        self::assertEquals([], $graph->getEdgesOfNode('invalid'));
        self::assertEquals([], $graph->getNeighbors('invalid'));

        self::assertEquals(0, $graph->getDimension());
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
        self::assertTrue($graph->isCircleFree());

        self::assertEquals([], $graph->getBridges());
        self::assertEquals([], $graph->getCircle());
        self::assertEquals([], $graph->getFloydWarshallShortestPath());
        self::assertEquals([], $graph->getDijkstraShortestPath());
        self::assertEquals([], $graph->depthFirstTraversal());
        self::assertEquals([], $graph->breadthFirstTraversal());
        self::assertEquals([], $graph->longestPath());
        self::assertEquals([], $graph->longestPathBetweenNodes('invalid1', 'invalid2'));
        self::assertEquals([], $graph->getUnconnected());
    }
}
