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

namespace phpOMS\tests\Stdlib\Tree;

use phpOMS\Stdlib\Tree\BinarySearchTree;
use phpOMS\Stdlib\Tree\Node;

/**
 * @testdox phpOMS\tests\Stdlib\Tree\BinarySearchTreeTest: Priority queue
 *
 * @internal
 */
final class BinarySearchTreeTest extends \PHPUnit\Framework\TestCase
{
    public function testBST() : void
    {
        $bst = new BinarySearchTree();
        $bst->insert(new Node('D', 'D'));
        $bst->insert(new Node('I', 'I'));
        $bst->insert(new Node('N', 'N'));
        $bst->insert(new Node('O', 'O'));
        $bst->insert(new Node('S', 'S'));
        $bst->insert(new Node('A', 'A'));
        $bst->insert(new Node('U', 'U'));
        $bst->insert(new Node('R', 'R'));
        $bst->delete($bst->search('I'));
        $bst->insert(new Node('Z', 'Z'));
        $bst->delete($bst->search('S'));
        $bst->insert(new Node('T', 'T'));

        self::assertEquals(
            [
                'key' => 'D',
                0 => ['key' => 'I'],
                1 => ['key' => 'I'],
            ],
            $bst->toArray()
        );
    }
}
