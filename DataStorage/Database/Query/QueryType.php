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
abstract class QueryType extends Enum
{
    public const SELECT = 0;

    public const INSERT = 1;

    public const UPDATE = 2;

    public const DELETE = 3;

    public const RANDOM = 4;

    public const RAW = 5;

    public const NONE = 6;
}
