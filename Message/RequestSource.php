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

namespace phpOMS\Message;

use phpOMS\Datatypes\Enum;

/**
 * Request source enum.
 *
 * @category   Request
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class RequestSource extends Enum
{
    /* public */ const WEB = 0; /* This is a http request */
    /* public */ const CONSOLE = 1; /* Request is a console command */
    /* public */ const SOCKET = 2; /* Request through socket connection */
}
