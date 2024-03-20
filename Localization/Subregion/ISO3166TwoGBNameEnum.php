<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Localization\Subregion
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Localization\Subregion;

use phpOMS\Stdlib\Base\Enum;

/**
 * Country codes ISO 3166-2 list.
 *
 * @package phpOMS\Localization\Subregion
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class ISO3166TwoGBNameEnum extends Enum
{
    public const _ENG = 'England';

    public const _NIR = 'Northern Ireland';

    public const _SCT = 'Scotland';

    public const _WLS = 'Wales';
}
