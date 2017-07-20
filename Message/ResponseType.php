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
 * Request type enum.
 *
 * @category   Framework
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class ResponseType extends Enum
{
    /* public */ const HTTP = 0; /* HTTP */
    /* public */ const SOCKET = 1; /* Socket */
    /* public */ const CONSOLE = 2; /* Console */
}
