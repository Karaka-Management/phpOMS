<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

use phpOMS\Stdlib\Base\Enum;

/**
 * Encoding enum.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class EncodingType extends Enum
{
    public const E_7BIT = '7bit';

    public const E_8BIT = '8bit';

    public const E_BASE64 = 'base64';

    public const E_BINARY = 'binary';

    public const E_QUOTED = 'quoted-printable';
}
