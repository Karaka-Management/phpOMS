<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Algorithm\Sort;
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

/**
 * SortInterface class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface SortInterface
{
    /**
     * Sort array
     *
     * @param array $list  List of sortable elements
     * @param int   $order Sort order
     *
     * @return array Sorted array
     *
     * @since 1.0.0
     */
    public static function sort(array $list, int $order = SortOrder::ASC) : array;
}
