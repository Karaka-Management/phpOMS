<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Uri
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Uri;

use phpOMS\Stdlib\Base\Enum;

/**
 * Uri scheme.
 *
 * @package phpOMS\Uri
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class UriScheme extends Enum
{
    public const HTTP = 0; /* Http */

    public const FILE = 1; /* File */

    public const MAILTO = 2; /* Mail */

    public const FTP = 3; /* FTP */

    public const HTTPS = 4; /* Https */

    public const IRC = 5; /* IRC */

    public const TEL = 6; /* Telephone */

    public const TELNET = 7; /* Telnet */

    public const SSH = 8; /* SSH */

    public const SKYPE = 9; /* Skype */

    public const SSL = 10; /* SSL */

    public const NFS = 11; /* Network File System */

    public const GEO = 12; /* Geo location */

    public const MARKET = 13; /* Android Market */

    public const ITMS = 14; /* iTunes */
}
