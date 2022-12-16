<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Converter
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
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
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class EnergyPowerType extends Enum
{
    public const KILOWATT_HOUERS = 'kWh';

    public const MEGAWATT_HOUERS = 'MWh';

    public const KILOTONS = 'kt';

    public const JOULS = 'J';

    public const CALORIES = 'Cal';

    public const BTU = 'BTU';

    public const KILOJOULS = 'kJ';

    public const THERMEC = 'thmEC';

    public const NEWTON_METERS = 'Nm';
}
