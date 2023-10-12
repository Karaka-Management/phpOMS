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
 * Priority queue class.
 *
 * @package phpOMS\Stdlib\Tree
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Node
{
    public string $key = '';

    public mixed $data = null;

    public ?BinarySearchTree $left = null;

    public ?BinarySearchTree $right = null;

    public ?self $parent = null;

    public ?BinarySearchTree $tree = null;

    public function __construct(string $key, mixed $data = null)
    {
        $this->key = $key;
        $this->data = $data;
    }

    public function compare(mixed $data) : int
    {
        return $this->data <=> $data;
    }
}