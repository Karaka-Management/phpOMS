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
namespace phpOMS\Message;

use phpOMS\Datatypes\Enum;

/**
 * Request page enum.
 *
 * Possible page requests. Page requests can have completely different themes, permissions and page structures.
 *
 * @category   Request
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class RequestDestination extends Enum
{
    const WEBSITE  = 'Website';     /* Website */
    const API      = 'Api';         /* API */
    const SHOP     = 'Shop';        /* Shop */
    const BACKEND  = 'Backend';     /* Backend */
    const STATICP  = 'Static';      /* Static content */
    const FORUM    = 'Forum';       /* Forum */
    const TICKET   = 'Ticket';      /* ???? */
    const SUPPORT  = 'Support';     /* Support center */
    const SURVEY   = 'Survey';      /* Survey page */
    const BLOG     = 'Blog';        /* Blog */
    const CHART    = 'Chart';       /* Chart view */
    const CALENDAR = 'Calendar';    /* Calendar */
    const PROFILE  = 'Profile';     /* User profile page */
    const CHAT     = 'Chat';        /* Chat page */
    const GALLERY  = 'Gallery';     /* Chat page */
    const REPORTER = 'Reporter';    /* Reporter page */
    // This or let api handle this const GUI = 'gui';     /* Request GUI elements */
}
