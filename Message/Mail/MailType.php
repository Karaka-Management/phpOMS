<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Message\Mail;

use phpOMS\Datatypes\Enum;

/**
 * Mail type.
 *
 * @category   Framework
 * @package    phpOMS\Message\Mail
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class MailType extends Enum
{
    const MAIL = 0;
    const SMTP = 1;
    const IMAP = 2;
    const POP3 = 3;
    const SENDMAIL = 4;
}
