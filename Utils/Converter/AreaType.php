<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils\Converter
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\Converter;

use phpOMS\Stdlib\Base\Enum;

/**
 * Area type enum.
 *
 * @package phpOMS\Utils\Converter
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class AreaType extends Enum
{
    public const SQUARE_FEET        = 'ft';
    public const SQUARE_METERS      = 'm';
    public const SQUARE_KILOMETERS  = 'km';
    public const SQUARE_MILES       = 'mi';
    public const SQUARE_YARDS       = 'yd';
    public const SQUARE_INCHES      = 'in';
    public const SQUARE_MICROINCHES = 'muin';
    public const SQUARE_CENTIMETERS = 'cm';
    public const SQUARE_MILIMETERS  = 'mm';
    public const SQUARE_MICROMETERS = 'micron';
    public const SQUARE_DECIMETERS  = 'dm';
    public const HECTARES           = 'ha';
    public const ACRES              = 'ac';
}
