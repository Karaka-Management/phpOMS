<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
 * Temperature type enum.
 *
 * @package phpOMS\Utils\Converter
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class TemperatureType extends Enum
{
    public const CELSIUS = 'celsius';

    public const FAHRENHEIT = 'fahrenheit';

    public const KELVIN = 'kelvin';

    public const REAUMUR = 'reaumur';

    public const RANKINE = 'rankine';

    public const DELISLE = 'delisle';

    public const NEWTON = 'newton';

    public const ROMER = 'romer';
}
