<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Stdlib\Graph;

use phpOMS\Stdlib\Graph\Edge;
use phpOMS\Stdlib\Graph\Node;

class EdgeTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
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
