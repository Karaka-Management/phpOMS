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
namespace phpOMS\Message\Http;

use phpOMS\Datatypes\Enum;

/**
 * Browser type enum.
 *
 * Browser types can be used for statistics or in order to deliver browser specific content.
 *
 * @category   Request
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class BrowserType extends Enum
{
    const IE        = 'msie'; /* Internet Explorer */
    const FIREFOX   = 'firefox'; /* Firefox */
    const SAFARI    = 'safari'; /* Safari */
    const CHROME    = 'chrome'; /* Chrome */
    const OPERA     = 'opera'; /* Opera */
    const NETSCAPE  = 'netscape'; /* Netscape */
    const MAXTHON   = 'maxthon'; /* Maxthon */
    const KONQUEROR = 'konqueror'; /* Konqueror */
    const HANDHELD  = 'mobile'; /* Handheld Browser */
}
