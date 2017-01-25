<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Uri;

use phpOMS\Datatypes\Enum;

/**
 * Uri scheme.
 *
 * @category   Framework
 * @package    phpOMS/Uri
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class UriScheme extends Enum
{
    /* public */ const HTTP = 0; /* Http */
    /* public */ const FILE = 1; /* File */
    /* public */ const MAILTO = 2; /* Mail */
    /* public */ const FTP = 3; /* FTP */
    /* public */ const HTTPS = 4; /* Https */
    /* public */ const IRC = 5; /* IRC */
    /* public */ const TEL = 6; /* Telephone */
    /* public */ const TELNET = 7; /* Telnet */
    /* public */ const SSH = 8; /* SSH */
    /* public */ const SKYPE = 9; /* Skype */
    /* public */ const SSL = 10; /* SSL */
    /* public */ const NFS = 11; /* Network File System */
    /* public */ const GEO = 12; /* Geo location */
    /* public */ const MARKET = 13; /* Android Market */
    /* public */ const ITMS = 14; /* iTunes */
}
