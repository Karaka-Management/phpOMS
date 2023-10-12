<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Stdlib\Tree
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Tree;

/**
 * Binary search tree.
 *
 * @package phpOMS\Stdlib\Tree
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class BinarySearchTree
{
    public ?Node $root = null;

    public function __construct(Node $root = null)
    {
        $this->root = $root;
    }

    public function search(mixed $data) : ?Node
    {
        if ($this->root === null) {
            return null;
        }

        $comparison = $this->root->compare($data);

        if ($comparison > 0) {
            return $this->root->left->search($data);
        } elseif ($comparison < 0) {
            return $this->root->right->search($data);
        }

        return $this->root;
    }

    public function minimum() : ?Node
    {
        if ($this->root === null) {
            return null;
        }

        if ($this->root->left === null) {
            return $this->root;
        }

        return $this->root->left->minimum();
    }

    public function maximum() : ?Node
    {
        if ($this->root === null) {
            return null;
        }

        if ($this->root->right === null) {
            return $this->root;
        }

        return $this->root->right->minimum();
    }

    public function predecessor(Node $node) : ?Node
    {
        if ($node->left !== null) {
            return $node->left->maximum();
        }

        $top = $node->parent;
        while ($top !== Null && $top->compare($node->data)) {
            $node = $top;
            $top = $top->parent;
        }

        return $top;
    }

    public function successor(Node $node) : ?Node
    {
        if ($node->right !== null) {
            return $node->right->minimum();
        }

        $top = $node->parent;
        while ($top !== null && $top->compare($node->data)) {
            $node = $top;
            $top = $top->parent;
        }

        return $top;
    }

    public function insert(Node $node) : void
    {
        if ($this->root === null) {
            $new = new Node($node->key, $node->data);
            $new->parent = null;
            $new->tree = $this;

            $this->root = $new;

            return;
        }

        $current = $this->root;
        while (true) {
            $comparison = $node->compare($current->data);

            if ($comparison < 0) {
                if ($current->left === null) {
                    $BST = new BinarySearchTree();
                    $new = new Node($node->key, $node->data);
                    $new->parent = $current;
                    $new->tree = $BST;

                    $BST->root = $new;
                    $current->left = $BST;
                } else {
                    $current = $current->left->root;
                }
            } elseif ($comparison > 0) {
                if ($current->right === null) {
                    $BST = new BinarySearchTree();
                    $new = new Node($node->key, $node->data);
                    $new->parent = $current;
                    $new->tree = $BST;

                    $BST->root = $new;
                    $current->right = $BST;
                } else {
                    $current = $current->right->root;
                }
            }

            return;
        }
    }

    public function delete(Node &$node) : void
    {
        if ($node->left === null && $node->right === null) {
            if ($node->parent !== null) {
                if ($node->parent->left !== null && $node->parent->left->root->compare($node->data) === 0) {
                    $node->parent->left = null;
                } elseif ($node->parent->right !== null && $node->parent->right->root->compare($node) === 0) {
                    $node->parent->right = null;
                }
            }

            $node = null;

            return;
        }

        $temp = null;
        if ($node->left === null) {
            $temp = $node->right->root;
            if ($node->parent !== null) {
                if ($node->parent->left !== null && $node->parent->left->root->compare($node->data) === 0) {
                    $node->parent->left = $temp->tree;
                } elseif ($node->parent->right !== null && $node->parent->right->root->compare($node->data) === 0) {
                    $node->parent->right = $temp->tree;
                }
            }

            $temp->parent = $node->parent;

            $node = null;

            return;
        }

        if ($node->right === null) {
            $temp = $node->left->root;
            if ($node->parent !== null) {
                if ($node->parent->left !== null && $node->parent->left->root->compare($node->data) === 0) {
                    $node->parent->left = $temp->tree;
                } elseif ($node->parent->right !== null && $node->parent->right->root->compare($node->data) === 0) {
                    $node->parent->right = $temp->tree;
                }
            }

            $temp->parent = $node->parent;

            $node = null;

            return;
        } else {
            $temp = $this->successor($node);
            $node->key = $temp->key;
            $node->data = $temp->data;

            $this->delete($temp);
        }
    }
}