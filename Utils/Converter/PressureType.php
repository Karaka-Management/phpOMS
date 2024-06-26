<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\Converter
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Converter;

use phpOMS\Stdlib\Base\Enum;

/**
 * Pressure type enum.
 *
 * @package phpOMS\Utils\Converter
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class PressureType extends Enum
{
    public const PASCALS = 'Pa';

    public const BAR = 'bar';

    public const POUND_PER_SQUARE_INCH = 'psi';

    public const ATMOSPHERES = 'atm';

    public const INCHES_OF_MERCURY = 'inHg';

    public const INCHES_OF_WATER = 'inH20';

    public const MILLIMETERS_OF_WATER = 'mmH20';

    public const MILLIMETERS_OF_MERCURY = 'mmHg';

    public const MILLIBAR = 'mbar';

    public const KILOGRAM_PER_SQUARE_METER = 'kg/m2';

    public const NEWTONS_PER_METER_SQUARED = 'N/m2';

    public const POUNDS_PER_SQUARE_FOOT = 'psf';

    public const TORRS = 'Torr';
}
