<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\Converter
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Converter;

use phpOMS\Stdlib\Base\Enum;

/**
 * Energy/Power type enum.
 *
 * @package phpOMS\Utils\Converter
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class EnergyPowerType extends Enum
{
    public const KILOWATT_HOURS = 'kWh';

    public const MEGAWATT_HOURS = 'MWh';

    public const KILOTONS = 'kt';

    public const JOULES = 'J';

    public const CALORIES = 'Cal';

    public const BTU = 'BTU';

    public const KILOJOULES = 'kJ';

    public const THERMEC = 'thmEC';

    public const NEWTON_METERS = 'Nm';
}
