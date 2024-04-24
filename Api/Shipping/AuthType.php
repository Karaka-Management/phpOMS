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
 * Auth Type
 *
 * @package phpOMS\Api\Shipping
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class AuthType extends Enum
{
    public const AUTOMATIC_LOGIN = 2;

    public const MANUAL_LOGIN = 4;

    public const KEY_LOGIN = 8;
}
