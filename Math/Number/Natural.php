<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types = 1);

namespace phpOMS\Math\Number;

/**
 * Natura number class
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Natural
{
    /**
     * Is natural number.
     *
     * @param mixed $value Value to test
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function isNatural($value) : bool
    {
        return is_int($value) && $value >= 0;
    }
}