<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Validation\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Validation\Finance;

use phpOMS\Stdlib\Base\Enum;

/**
 * Iban error type enum.
 *
 * @package phpOMS\Validation\Finance
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class IbanErrorType extends Enum
{
    public const INVALID_COUNTRY = 1;

    public const INVALID_LENGTH = 2;

    public const INVALID_CHECKSUM = 4;

    public const EXPECTED_ZERO = 8;

    public const EXPECTED_NUMERIC = 16;
}
