<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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
 * SortableInterface class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface SortableInterface
{
    /**
     * Compare current object with other object
     *
     * @param SortableInterface $obj   Object to compare with
     * @param int               $order Sort order
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function compare(self $obj, int $order = SortOrder::ASC) : bool;

    /**
     * Get element value
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getValue();

    /**
     * Is value the same
     *
     * @param SortableInterface $obj Object to compare with
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function equals(self $obj) : bool;

    /**
     * Get maximum element
     *
     * @param SortableInterface[] $list List to order
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function max(array $list);

    /**
     * Get minimum element
     *
     * @param SortableInterface[] $list List to order
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function min(array $list);
}
