<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Database\Query
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

use phpOMS\Stdlib\Base\Enum;

/**
 * Query type enum.
 *
 * @package phpOMS\DataStorage\Database\Query
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class OrderType extends Enum
{
    public const ASC = 'ASC';

    public const DESC = 'DESC';
}
