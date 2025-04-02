<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Api\Shipping
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Shipping;

use phpOMS\Stdlib\Base\Enum;

/**
 * Auth Status
 *
 * @package phpOMS\Api\Shipping
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class AuthStatus extends Enum
{
    public const OK = 0;

    public const FAILED = -1;

    public const BLOCKED = -2;

    public const LIMIT_EXCEEDED = -3;
}
