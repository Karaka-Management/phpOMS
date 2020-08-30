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
 * Time type enum.
 *
 * @package phpOMS\Utils\Converter
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class TimeType extends Enum
{
    public const MILLISECONDS = 'ms';

    public const SECONDS      = 's';

    public const MINUTES      = 'i';

    public const HOURS        = 'h';

    public const DAYS         = 'd';

    public const WEEKS        = 'w';

    public const MONTH        = 'm';

    public const QUARTER      = 'q';

    public const YEAR         = 'y';
}
