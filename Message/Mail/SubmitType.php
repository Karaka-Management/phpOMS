<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

use phpOMS\Stdlib\Base\Enum;

/**
 * Submit enum.
 *
 * @package  phpOMS\Message\Mail
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class SubmitType extends Enum
{
    public const SMTP     = 'smtp';

    public const MAIL     = 'mail';

    public const SENDMAIL = 'sendmail';

    public const QMAIL    = 'qmail';
}
