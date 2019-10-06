<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\Sort;
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

/**
 * SortInterface class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
