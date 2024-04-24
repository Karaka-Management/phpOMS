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
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Graph\Edge::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Stdlib\Graph\EdgeTest: Edge between nodes')]
final class EdgeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The edge has the expected default values after initialization')]
    public function testDefault() : void
    {
        $edge = new Edge(new Node('1'), new Node('2'));
        self::assertEquals([new Node('1'), new Node('2')], $edge->getNodes());
        self::assertTrue($edge->node1->isEqual(new Node('1')));
        self::assertTrue($edge->node2->isEqual(new Node('2')));
        self::assertEquals(1.0, $edge->weight);
        self::assertFalse($edge->isDirected);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An edge can be directed')]
    public function testDirected() : void
    {
        $edge = new Edge(new Node('7'), new Node('8'), 1.0, true);
        self::assertTrue($edge->isDirected);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An edge weight can be set and returned')]
    public function testWeightInputOutput() : void
    {
        $edge = new Edge(new Node('7'), new Node('8'), 2.0, true);
        self::assertEquals(2.0, $edge->weight);

        $edge         = new Edge(new Node('7'), new Node('8'), 1.0);
        $edge->weight = 3.0;
        self::assertEquals(3.0, $edge->weight);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Two edge weights can be compared')]
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
