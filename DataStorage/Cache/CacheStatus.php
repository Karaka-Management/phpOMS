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
namespace phpOMS\DataStorage\Cache;

use phpOMS\Datatypes\Enum;

/**
 * Cache status enum.
 *
 * Possible caching status
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Cache
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class CacheStatus extends Enum
{
    const INACTIVE = 0; /* Caching is disabled */
    const ERROR = 1; /* Caching failed */
    const MEMCACHE = 2; /* Caching OK */
    const FILECACHE = 3; /* Caching OK */
    const REDISCACHE = 4; /* Caching OK */
    const WINCACHE = 5; /* Caching OK */
}
