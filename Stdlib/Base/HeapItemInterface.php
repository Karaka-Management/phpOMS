<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Stdlib\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

/**
 * Item interface
 *
 * @package phpOMS\Stdlib\Base
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
interface HeapItemInterface
{
    /**
     * Compare heap items
     *
     * @param HeapItemInterface $item Heap item
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isEqual(self $item) : bool;
}
