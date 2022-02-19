<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

use phpOMS\Stdlib\Base\Enum;

/**
 * SMTP auth types enum.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class SMTPAuthType extends Enum
{
    public const NONE = '';

    public const CRAM = 'CRAM-MD5';

    public const LOGIN = 'LOGIN';

    public const PLAIN = 'PLAIN';

    public const XOAUTH2 = 'XOAUTH2';
}
