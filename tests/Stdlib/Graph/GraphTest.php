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

/**
 * @internal
 */
class GraphTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $graph = new Graph();

        self::assertNull($graph->getNode('invalid'));
        self::assertEquals([], $graph->getNodes());

        self::assertNull($graph->getEdge('invalid'));
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
