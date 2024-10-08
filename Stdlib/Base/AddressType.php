<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Stdlib\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

/**
 * Address type enum.
 *
 * @package phpOMS\Stdlib\Base
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class AddressType extends Enum
{
    public const HOME = 1;

    public const BUSINESS = 2;

    public const SHIPPING = 3;

    public const BILLING = 4;

    public const WORK = 5;

    public const EDUCATION = 8;

    public const OTHER = 99;
}
