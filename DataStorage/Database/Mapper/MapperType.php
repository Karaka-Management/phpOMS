<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Mapper
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Mapper;

use phpOMS\Stdlib\Base\Enum;

/**
 * Mapper type enum.
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class MapperType extends Enum
{
    public const GET = 1;

    public const GET_YIELD = 2;

    public const GET_ALL = 4;

    public const FIND = 7;

    public const GET_RAW = 8;

    public const GET_RANDOM = 11;

    public const COUNT_MODELS = 12;

    public const SUM_MODELS = 13;

    public const MODEL_EXISTS = 14;

    public const MODEL_HAS_RELATION = 15;

    // -------------------------------------------- //

    public const CREATE = 1001;

    // -------------------------------------------- //

    public const UPDATE = 2001;

    // -------------------------------------------- //

    public const DELETE = 3001;
}
