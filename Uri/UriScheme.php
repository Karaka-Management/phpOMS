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
    const HTTP = 0; /* Http */
    const FILE = 1; /* File */
    const MAILTO = 2; /* Mail */
    const FTP = 3; /* FTP */
    const HTTPS = 4; /* Https */
    const IRC = 5; /* IRC */
    const TEL = 6; /* Telephone */
    const TELNET = 7; /* Telnet */
    const SSH = 8; /* SSH */
    const SKYPE = 9; /* Skype */
    const SSL = 10; /* SSL */
    const NFS = 11; /* Network File System */
    const GEO = 12; /* Geo location */
    const MARKET = 13; /* Android Market */
    const ITMS = 14; /* iTunes */
}
