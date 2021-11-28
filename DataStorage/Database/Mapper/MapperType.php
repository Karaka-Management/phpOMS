<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Database\Mapper
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Mapper;

use phpOMS\Stdlib\Base\Enum;

/**
 * Mapper type enum.
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class MapperType extends Enum
{
    public const GET = 1;

    public const GET_ALL = 4;

    public const FIND = 7;

    public const GET_RAW = 8;

    public const GET_RANDOM = 11;

    public const COUNT_MODELS = 12;

    // -------------------------------------------- //

    public const CREATE = 1001;

    // -------------------------------------------- //

    public const UPDATE = 2001;

    // -------------------------------------------- //

    public const DELETE = 3001;
}
