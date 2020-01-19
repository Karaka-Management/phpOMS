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
}
