<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Stdlib\Graph
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Graph;

/**
 * Tree class.
 *
 * @package phpOMS\Stdlib\Graph
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class BinaryTree extends Tree
{
    /**
     * Invert the tree
     *
     * @return BinaryTree
     *
     * @since 1.0.0
     */
    public static function invert($list) : self
    {
        if (empty($list->getNodes())) {
            return $list;
        }

        $left = $list->getLeft();
        $list->setLeft($list->invert($list->nodes[1]));
        $list->setRight($list->invert($left));

        return $list;
    }

    /**
     * Get left node of a node.
     *
     * @param Node $base Tree node
     *
     * @return null|Node Left node
     *
     * @since 1.0.0
     */
    public function getLeft(Node $base) : ?Node
    {
        $neighbors = $base->getNeighbors($base);

        // todo: index can be wrong, see setLeft/setRight
        return $neighbors[0] ?? null;
    }

    /**
     * Get right node of a node.
     *
     * @param Node $base Tree node
     *
     * @return null|Node Right node
     *
     * @since 1.0.0
     */
    public function getRight(Node $base) : ?Node
    {
        $neighbors = $this->getNeighbors($base);

        // todo: index can be wrong, see setLeft/setRight
        return $neighbors[1] ?? null;
    }

    /**
     * Set left node of node.
     *
     * @param Node $base Base node
     * @param Node $left Left node
     *
     * @return BinaryTree
     *
     * @since 1.0.0
     */
    public function setLeft(Node $base, Node $left) : self
    {
        if ($this->getLeft($base) === null) {
            $this->addNodeRelative($base, $left);
            // todo: doesn't know that this is left
            // todo: maybe need to add numerics to edges?
        } else {
            // todo: replace node
            $a = 2;
        }

        return $this;
    }

    /**
     * Set right node of node.
     *
     * @param Node $base  Base node
     * @param Node $right Right node
     *
     * @return BinaryTree
     *
     * @since 1.0.0
     */
    public function setRight(Node $base, Node $right)  : self
    {
        if ($this->getRight($base) === null) {
            $this->addNodeRelative($base, $right);
            // todo: doesn't know that this is right
            // todo: maybe need to add numerics to edges?
        } else {
            // todo: replace node
            $a = 2;
        }

        return $this;
    }

    /**
     * Perform action on tree in in-order.
     *
     * @param Node     $node     Tree node
     * @param \Closure $callback Task to perform on node
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function inOrder(Node $node, \Closure $callback) : void
    {
        $this->inOrder($this->getLeft($node), $callback);
        $callback($node);
        $this->inOrder($this->getRight($node), $callback);
    }

    /**
     * Get nodes in vertical order.
     *
     * @param Node   $node               Tree node
     * @param int    $horizontalDistance Horizontal distance
     * @param Node[] $order              Ordered nodes by horizontal distance
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function getVerticalOrder(Node $node, int $horizontalDistance, array &$order) : void
    {
        if (!isset($order[$horizontalDistance])) {
            $order[$horizontalDistance] = [];
        }

        $order[$horizontalDistance][] = $node;
        $left                         = $this->getLeft($node);
        $right                        = $this->getRight($node);

        if ($left !== null) {
            $this->getVerticalOrder($left, $horizontalDistance - 1, $order);
        }

        if ($right !== null) {
            $this->getVerticalOrder($right, $horizontalDistance + 1, $order);
        }
    }

    /**
     * Perform action on tree in vertical-order.
     *
     * @param Node     $node     Tree node
     * @param \Closure $callback Task to perform on node
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function verticalOrder(Node $node, \Closure $callback) : void
    {
        $order = [];
        $this->getVerticalOrder($node, 0, $order);

        foreach ($order as $level) {
            foreach ($level as $node) {
                $callback($node);
            }
        }
    }

    /**
     * Check if tree is symmetric.
     *
     * @param null|Node $node1 Tree node1
     * @param null|Node $node2 Tree node2 (optional, can be different tree)
     *
     * @return bool True if tree is symmetric, false if tree is not symmetric
     *
     * @since 1.0.0
     */
    public function isSymmetric(Node $node1 = null, Node $node2 = null) : bool
    {
        if (($node1 === null && $node2 === null)
            || $node1->isEqual($node2)
        ) {
            return true;
        } elseif ($node1 === null || $node2 === null) {
            return false;
        }

        $left1  = $this->getLeft($node1);
        $right1 = $this->getRight($node1);

        $left2  = $node2 !== null ? $this->getLeft($node1) : null;
        $right2 = $node2 !== null ? $this->getRight($node1) : null;

        return $this->isSymmetric($left1, $right2) && $this->isSymmetric($right1, $left2);
    }
}
