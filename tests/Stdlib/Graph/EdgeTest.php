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

use phpOMS\Stdlib\Graph\Edge;
use phpOMS\Stdlib\Graph\Node;

/**
 * @internal
 */
class EdgeTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $edge = new Edge(new Node(), new Node());
        self::assertEquals([new Node(), new Node()], $edge->getNodes());
        self::assertEquals(new Node(), $edge->getNode1());
        self::assertEquals(new Node(), $edge->getNode2());
        self::assertEquals(0.0, $edge->getWeight());
        self::assertFalse($edge->isDirected());

        $edge = new Edge(new Node(), new Node(), 0.0, true);
        self::assertTrue($edge->isDirected());
    }
}
