<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Stdlib\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

/**
 * Phone type enum.
 *
 * @package phpOMS\Stdlib\Base
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class PhoneType extends Enum
{
    public const HOME = 1;

    public const BUSINESS = 2;

    public const MOBILE = 3;

    public const WORK = 4;
}
