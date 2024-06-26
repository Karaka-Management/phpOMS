<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Stdlib\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

/**
 * Item interface
 *
 * @package phpOMS\Stdlib\Base
 * @license OMS License 2.0
 * @link    https://jingga.app
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
