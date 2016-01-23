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
namespace phpOMS\Datatypes;

/**
 * Enum class.
 *
 * Replacing the SplEnum class and providing basic enum.
 *
 * @category   Framework
 * @package    phpOMS\Datatypes
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class Enum
{

    /**
     * Checking enum name.
     *
     * Checking if a certain const name exists (case sensitive)
     *
     * @param string $name Name of the value (case sensitive)
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function isValidName(string $name) : bool
    {
        $constants = self::getConstants();

        return isset($constants[$name]);
    }

    /**
     * Getting all constants of this enum.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getConstants() : array
    {
        $reflect = new \ReflectionClass(get_called_class());

        return $reflect->getConstants();
    }

    /**
     * Check enum value.
     *
     * Checking if a given value is part of this enum
     *
     * @param mixed $value Value to check
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function isValidValue($value) : bool
    {
        $values = array_values(self::getConstants());

        return in_array($value, $values, true);
    }

}
