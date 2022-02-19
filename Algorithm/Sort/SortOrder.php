<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Algorithm\Sort
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

use phpOMS\Stdlib\Base\Enum;

/**
 * SortOrder enum.
 *
 * @package phpOMS\Algorithm\Sort
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class SortOrder extends Enum
{
    public const ASC = 1;

    public const DESC = 2;
}
