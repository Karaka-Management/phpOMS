<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\PathFinding;

use phpOMS\Algorithm\PathFinding\Node;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\PathFinding\Node::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\PathFinding\NodeTest: Node on grid for path finding')]
final class NodeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The node has the expected values after initialization')]
    public function testDefault() : void
    {
        $node = new Node(1, 2, 3.0, false);

        self::assertEquals(1, $node->getX());
        self::assertEquals(2, $node->getY());
        self::assertEquals(['x' => 1, 'y' => 2], $node->getCoordinates());
        self::assertEquals(3.0, $node->getWeight());
        self::assertNull($node->parent);
        self::assertFalse($node->isWalkable);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Nodes with equal coordinates are equal')]
    public function testNodesWithEqualCoordinatesAreEqual() : void
    {
        $node  = new Node(1, 2, 3.0, false);
        $node2 = new Node(1, 2, 2.0, true);

        self::assertTrue($node->isEqual($node2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Nodes with different coordinates are not equal')]
    public function testNodesWithDifferentCoordinatesAreNotEqual() : void
    {
        $node  = new Node(1, 2, 3.0, false);
        $node2 = new Node(2, 2, 3.0, false);

        self::assertFalse($node->isEqual($node2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A parent node can be set and returned')]
    public function testParentInputOutput() : void
    {
        $node  = new Node(1, 2, 3.0, false);
        $node2 = new Node(2, 2, 3.0, false);

        $node->parent = $node2;
        self::assertTrue($node2->isEqual($node->parent));
    }
}
