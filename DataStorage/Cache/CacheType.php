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
 * Cache type enum.
 *
 * Possible caching types
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Cache
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class CacheType extends Enum
{
    /* public */ const _INT = 0; /* Data is integer */
    /* public */ const _STRING = 1; /* Data is string */
    /* public */ const _ARRAY = 2; /* Data is array */
    /* public */ const _SERIALIZABLE = 3; /* Data is object */
    /* public */ const _JSONSERIALIZABLE = 6;
    /* public */ const _FLOAT = 4; /* Data is float */
    /* public */ const _BOOL = 5; /* Data is float */
}
