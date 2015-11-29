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
    const WEBSITE  = 'website';     /* Website */
    const API      = 'api';         /* API */
    const SHOP     = 'shop';        /* Shop */
    const BACKEND  = 'backend';     /* Backend */
    const STATICP  = 'static';      /* Static content */
    const FORUM    = 'forum';       /* Forum */
    const TICKET   = 'ticket';      /* ???? */
    const SUPPORT  = 'support';     /* Support center */
    const SURVEY   = 'survey';      /* Survey page */
    const BLOG     = 'blog';        /* Blog */
    const CHART    = 'chart';       /* Chart view */
    const CALENDAR = 'calendar';    /* Calendar */
    const PROFILE  = 'profile';     /* User profile page */
    const CHAT     = 'chat';        /* Chat page */
    const GALLERY  = 'gallery';     /* Chat page */
    const REPORTER = 'reporter';    /* Reporter page */
    // This or let api handle this const GUI = 'gui';     /* Request GUI elements */
}
