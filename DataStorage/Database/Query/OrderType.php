<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database\Query
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

use phpOMS\Stdlib\Base\Enum;

/**
 * Query type enum.
 *
 * @package phpOMS\DataStorage\Database\Query
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class OrderType extends Enum
{
    public const ASC = 'ASC';

    public const DESC = 'DESC';
}
