<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Algorithm\Sort
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

use phpOMS\Stdlib\Base\Enum;

/**
 * SortOrder enum.
 *
 * @package phpOMS\Algorithm\Sort
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class SortOrder extends Enum
{
    public const ASC = 1;

    public const DESC = 2;
}
