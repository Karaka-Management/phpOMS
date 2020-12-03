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
 * @testdox phpOMS\tests\Stdlib\Graph\EdgeTest: Edge between nodes
 *
 * @internal
 */
class EdgeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The edge has the expected default values after initialization
     * @covers phpOMS\Stdlib\Graph\Edge
     * @group framework
     */
    public function testDefault() : void
    {
        $edge = new Edge(new Node('1'), new Node('2'));
        self::assertEquals([new Node('1'), new Node('2')], $edge->getNodes());
        self::assertTrue($edge->getNode1()->isEqual(new Node('1')));
        self::assertTrue($edge->getNode2()->isEqual(new Node('2')));
        self::assertEquals(0.0, $edge->getWeight());
        self::assertFalse($edge->isDirected());
    }

    /**
     * @testdox An edge can be directed
     * @covers phpOMS\Stdlib\Graph\Edge
     * @group framework
     */
    public function testDirected() : void
    {
        $edge = new Edge(new Node('7'), new Node('8'), 1.0, true);
        self::assertTrue($edge->isDirected());
    }

    /**
     * @testdox An edge weight can be set and returned
     * @covers phpOMS\Stdlib\Graph\Edge
     * @group framework
     */
    public function testWeightInputOutput() : void
    {
        $edge = new Edge(new Node('7'), new Node('8'), 1.0, true);
        self::assertEquals(1.0, $edge->getWeight());

        $edge = new Edge(new Node('7'), new Node('8'), 1.0);
        $edge->setWeight(3.0);
        self::assertEquals(3.0, $edge->getWeight());
    }

    /**
     * @testdox Two edge weights can be compared
     * @covers phpOMS\Stdlib\Graph\Edge
     * @group framework
     */
    public function testWeightComparison() : void
    {
        $edge1 = new Edge(new Node('7'), new Node('8'), 1.0, true);
        $edge2 = new Edge(new Node('7'), new Node('8'), 1.0, true);
        $edge3 = new Edge(new Node('7'), new Node('8'), 2.0, true);

        self::assertEquals(0, Edge::compare($edge1, $edge2));
        self::assertEquals(-1, Edge::compare($edge1, $edge3));
        self::assertEquals(1, Edge::compare($edge3, $edge1));
    }
}
