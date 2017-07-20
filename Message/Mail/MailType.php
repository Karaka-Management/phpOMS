<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

use phpOMS\Datatypes\Enum;

/**
 * Mail type.
 *
 * @category   Framework
 * @package    phpOMS\Message\Mail
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class MailType extends Enum
{
    /* public */ const MAIL = 0;
    /* public */ const SMTP = 1;
    /* public */ const IMAP = 2;
    /* public */ const POP3 = 3;
    /* public */ const SENDMAIL = 4;
}
