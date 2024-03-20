<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\PathFinding;

use phpOMS\Algorithm\PathFinding\AStarNode;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\PathFinding\AStarNode::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\PathFinding\AStarNode: AStarNode on grid for path finding')]
final class AStarNodeTest extends \PHPUnit\Framework\TestCase
{
    protected $node;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->node = new AStarNode(1, 2, 3.0, false);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The node has the expected values after initialization')]
    public function testDefault() : void
    {
        self::assertFalse($this->node->isClosed());
        self::assertFalse($this->node->isOpened());
        self::assertEquals(0.0, $this->node->getG());
        self::assertNull($this->node->getH());
        self::assertEquals(0.0, $this->node->getF());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The node can be set closed and checked')]
    public function testClosedInputOutput() : void
    {
        $this->node->setClosed(true);
        self::assertTrue($this->node->isClosed());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The node can be set opened and checked')]
    public function testOpenedInputOutput() : void
    {
        $this->node->setOpened(true);
        self::assertTrue($this->node->isOpened());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The g value cen be set and returned')]
    public function testGInputOutput() : void
    {
        $this->node->setG(2.0);
        self::assertEquals(2.0, $this->node->getG());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The h value cen be set and returned')]
    public function testHInputOutput() : void
    {
        $this->node->setH(2.0);
        self::assertEquals(2.0, $this->node->getH());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The f value cen be set and returned')]
    public function testFInputOutput() : void
    {
        $this->node->setF(2.0);
        self::assertEquals(2.0, $this->node->getF());
    }
}
