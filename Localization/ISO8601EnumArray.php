<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Localization
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Localization;

use phpOMS\Stdlib\Base\EnumArray;

/**
 * Datetime ISO format.
 *
 * Careful only (1) is considered as the ISO8601 standard. This file is only supposed to
 * contain all plausible datetime strings.
 *
 * @package phpOMS\Localization
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class ISO8601EnumArray extends EnumArray
{
    protected static array $constants = [
        1 => 'YYYY-MM-DD hh:mm:ss', // ietf: rfc3339
        2 => 'YYYY.MM.DD hh:mm:ss',
        3 => 'DD-MM-YYYY hh:mm:ss',
        4 => 'DD.MM.YYYY hh:mm:ss',
    ];
}
