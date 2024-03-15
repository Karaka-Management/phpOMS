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

    // @IMPORTANT All read operations which use column names must have an ID < 12
    // In the read mapper exists a line which checks for < COUNT_MODELS to decide if columns should be selected.
    // The reason for this is that **pure** count, sum, ... don't want to select additional column names.
    // By doing this we avoid loading all the unwanted columns coming from the `with()` relation.

    // -------------------------------------------- //

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
