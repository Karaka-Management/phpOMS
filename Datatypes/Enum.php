<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
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
        $constants = self::getConstants();

        return in_array($value, $constants, true);
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
     * Get random enum value.
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getRandom()
    {
        $constants = self::getConstants();
        $keys      = array_keys($constants);

        return $constants[$keys[mt_rand(0, count($constants) - 1)]];
    }

    /**
     * Get enum value by name.
     *
     * @param string $name Enum name
     *
     * @return mixed
     *
     * @throws \Exception Throws this exception if the constant is not defined in the enum class.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getByName(string $name)
    {
        if (!self::isValidName($name)) {
            throw new \Exception('Undefined constant "' . $name . '"');
        }

        return constant('static::' . $name);
    }

    /**
     * Get enum name by value.
     *
     * @param string $value Enum value
     *
     * @return mixed
     *
     * @throws \Exception Throws this exception if the constant is not defined in the enum class.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getName(string $value)
    {
        $arr = self::getConstants();

        return array_search($value, $arr);
    }

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
        return defined('static::' . $name);
    }

}
