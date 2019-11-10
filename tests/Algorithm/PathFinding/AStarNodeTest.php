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

namespace phpOMS\tests\Algorithm\PathFinding;

use phpOMS\Algorithm\PathFinding\AStarNode;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\PathFinding\AStarNode: AStarNode on grid for path finding
 *
 * @internal
 */
class AStarNodeTest extends \PHPUnit\Framework\TestCase
{
    protected $node;

    protected function setUp() : void
    {
        $this->node = new AStarNode(1, 2, 3.0, false);
    }

    /**
     * @testdox The node has the expected values after initialization
     * @covers phpOMS\Algorithm\PathFinding\AStarNode
     */
    public function testDefault() : void
    {
        self::assertFalse($this->node->isClosed());
        self::assertFalse($this->node->isOpened());
        self::assertFalse($this->node->isTested());
        self::assertEquals(0.0, $this->node->getG());
        self::assertEquals(null, $this->node->getH());
        self::assertEquals(0.0, $this->node->getF());
    }

    /**
     * @testdox The node can be set closed and checked
     * @covers phpOMS\Algorithm\PathFinding\AStarNode
     */
    public function testClosedInputOutput() : void
    {
        $this->node->setClosed(true);
        self::assertTrue($this->node->isClosed());
    }

    /**
     * @testdox The node can be set opened and checked
     * @covers phpOMS\Algorithm\PathFinding\AStarNode
     */
    public function testOpenedInputOutput() : void
    {
        $this->node->setOpened(true);
        self::assertTrue($this->node->isOpened());
    }

    /**
     * @testdox The node can be set tested and checked
     * @covers phpOMS\Algorithm\PathFinding\AStarNode
     */
    public function testTestedInputOutput() : void
    {
        $this->node->setTested(true);
        self::assertTrue($this->node->isTested());
    }

    /**
     * @testdox The g value cen be set and returned
     * @covers phpOMS\Algorithm\PathFinding\AStarNode
     */
    public function testGInputOutput() : void
    {
        $this->node->setG(2.0);
        self::assertEquals(2.0, $this->node->getG());
    }

    /**
     * @testdox The h value cen be set and returned
     * @covers phpOMS\Algorithm\PathFinding\AStarNode
     */
    public function testHInputOutput() : void
    {
        $this->node->setH(2.0);
        self::assertEquals(2.0, $this->node->getH());
    }

    /**
     * @testdox The f value cen be set and returned
     * @covers phpOMS\Algorithm\PathFinding\AStarNode
     */
    public function testFInputOutput() : void
    {
        $this->node->setF(2.0);
        self::assertEquals(2.0, $this->node->getF());
    }
}
