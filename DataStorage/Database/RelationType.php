<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\Stdlib\Base\Enum;

/**
 * Relation type enum.
 *
 * Relations which can be used in order to specifiy how the DataMapper is supposed to work (level of detail)
 *
 * @package phpOMS\DataStorage\Database
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class RelationType extends Enum
{
    public const NONE = 1;

    public const NEWEST = 2;

    public const BELONGS_TO = 4;

    public const OWNS_ONE = 8;

    public const HAS_MANY = 16;

    public const ALL = 32;

    public const REFERENCE = 64;
}
