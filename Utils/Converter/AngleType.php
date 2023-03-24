<?php
/**
 * Karaka
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
 * Angle type enum.
 *
 * @package phpOMS\Utils\Converter
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class AngleType extends Enum
{
    public const DEGREE = 'deg';

    public const RADIAN = 'rad';

    public const SECOND = 'arcsec';

    public const MINUTE = 'arcmin';

    public const MILLIRADIAN_US = 'mil (us ww2)';

    public const MILLIRADIAN_UK = 'mil (uk)';

    public const MILLIRADIAN_USSR = 'mil (ussr)';

    public const MILLIRADIAN_NATO = 'mil (nato)';

    public const GRADIAN = 'g';

    public const CENTRAD = 'crad';
}
