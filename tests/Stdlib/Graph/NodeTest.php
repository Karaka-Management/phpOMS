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

use phpOMS\Stdlib\Graph\Node;

class NodeTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $node = new Node();
        self::assertEquals(null, $node->getData());
    }

    public function testGetSet()
    {
        $node = new Node(1);
        self::assertEquals(1, $node->getData());
        
        $node->setData(false);
        self::assertEquals(false, $node->getData());
    }
}
