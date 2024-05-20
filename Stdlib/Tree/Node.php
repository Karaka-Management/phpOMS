<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Stdlib\Tree
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Tree;

/**
 * Tree node class.
 *
 * @package phpOMS\Stdlib\Tree
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Node
{
    /**
     * Key of the node
     *
     * @var string
     * @since 1.0.0
     */
    public string $key = '';

    /**
     * Data of the node
     *
     * @var mixed
     * @since 1.0.0
     */
    public mixed $data = null;

    /**
     * Sub-tree to the left
     *
     * @var null|BinarySearchTree
     * @since 1.0.0
     */
    public ?BinarySearchTree $left = null;

    /**
     * Sub-tree to the right
     *
     * @var null|BinarySearchTree
     * @since 1.0.0
     */
    public ?BinarySearchTree $right = null;

    /**
     * Parent node
     *
     * @var null|Node
     * @since 1.0.0
     */
    public ?self $parent = null;

    /**
     * Parent tree
     *
     * @var null|BinarySearchTree
     * @since 1.0.0
     */
    public ?BinarySearchTree $tree = null;

    /**
     * Constructor.
     *
     * @param string $key  Node key
     * @param mixed  $data Node data
     *
     * @since 1.0.0
     */
    public function __construct(string $key, mixed $data = null)
    {
        $this->key  = $key;
        $this->data = $data;
    }

    /**
     * Compare node data
     *
     * @param mixed $data Node data to compare with
     *
     * @return -1|0|1
     *
     * @since 1.0.0
     */
    public function compare(mixed $data) : int
    {
        return $this->data <=> $data;
    }

    /**
     * To array
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function toArray() : array
    {
        return [
            'key' => $this->key,
            0     => $this->left?->toArray(),
            1     => $this->right?->toArray(),
        ];
    }
}
