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
 * Length type enum.
 *
 * @package phpOMS\Utils\Converter
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class LengthType extends Enum
{
    public const MILES = 'mi';

    public const METERS = 'm';

    public const NANOMETER = 'nm';

    public const MICROMETER = 'micron';

    public const CENTIMETERS = 'cm';

    public const MILLIMETERS = 'mm';

    public const KILOMETERS = 'km';

    public const CHAINS = 'ch';

    public const FEET = 'ft';

    public const FURLONGS = 'fur';

    public const MICROINCH = 'muin';

    public const INCHES = 'in';

    public const YARDS = 'yd';

    public const PARSECS = 'pc';

    public const UK_NAUTICAL_MILES = 'uk nmi';

    public const US_NAUTICAL_MILES = 'us nmi';

    public const UK_NAUTICAL_LEAGUES = 'uk nl';

    public const NAUTICAL_LEAGUES = 'nl';

    public const UK_LEAGUES = 'uk lg';

    public const US_LEAGUES = 'us lg';

    public const LIGHTYEARS = 'ly';

    public const DECIMETERS = 'dm';
}
