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
 declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Graph;

use phpOMS\Stdlib\Graph\Node;

/**
 * @internal
 */
class NodeTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $node = new Node();
        self::assertNull($node->getData());
    }

    public function testGetSet() : void
    {
        $node = new Node(1);
        self::assertEquals(1, $node->getData());

        $node->setData(false);
        self::assertFalse($node->getData());
    }
}
