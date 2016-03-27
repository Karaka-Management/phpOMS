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

namespace phpOMS\Utils\RnG;

/**
 * Linear congruential generator class
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class LinearCongruentialGenerator
{
    /**
     * BSD random number
     *
     * @return \Closure
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function bsd(int $seed)
    {
        return function() use(&$seed) {
            return $seed = (1103515245 * $seed + 12345) % (1 << 31);
        };
    }

    /**
     * MS random number
     *
     * @return \Closure
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function msvcrt(int $seed) {
        return function() use (&$seed) {
            return ($seed = (214013 * $seed + 2531011) % (1 << 31)) >> 16;
        };
    }
}
