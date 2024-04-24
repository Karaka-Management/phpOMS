<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Api\Shipping
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Shipping;

use phpOMS\Stdlib\Base\Enum;

/**
 * Shipping Type
 *
 * @package phpOMS\Api\Shipping
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class ShippingType extends Enum
{
    public const DHL = 1;

    public const DPD = 2;

    public const FEDEX = 3;

    public const ROYALMAIL = 4;

    public const TNT = 5;

    public const UPS = 6;

    public const USPS = 7;
}
