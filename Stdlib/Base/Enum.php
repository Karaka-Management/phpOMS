<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Stdlib\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

/**
 * Enum class.
 *
 * Replacing the SplEnum class and providing basic enum.
 *
 * @package phpOMS\Stdlib\Base
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
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
     * @since 1.0.0
     */
    public static function isValidValue(mixed $value) : bool
    {
        $constants = self::getConstants();

        return \in_array($value, $constants, true);
    }

    /**
     * Getting all constants of this enum.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getConstants() : array
    {
        $reflect = new \ReflectionClass(static::class);

        return $reflect->getConstants();
    }

    /**
     * Get random enum value.
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function getRandom() : mixed
    {
        $constants = self::getConstants();
        $keys      = \array_keys($constants);

        return $constants[$keys[\mt_rand(0, \count($constants) - 1)]];
    }

    /**
     * Get enum value by name.
     *
     * @param string $name Enum name
     *
     * @return mixed
     *
     * @throws \UnexpectedValueException throws this exception if the constant is not defined in the enum class
     *
     * @since 1.0.0
     */
    public static function getByName(string $name) : mixed
    {
        if (!self::isValidName($name)) {
            throw new \UnexpectedValueException($name);
        }

        return \constant('static::' . $name);
    }

    /**
     * Get enum name by value.
     *
     * @param string $value Enum value
     *
     * @return false|int|string
     *
     * @since 1.0.0
     */
    public static function getName(string $value) : bool | int | string
    {
        $arr = self::getConstants();

        return \array_search($value, $arr);
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
     * @since 1.0.0
     */
    public static function isValidName(string $name) : bool
    {
        return \defined('static::' . $name);
    }

    /**
     * Count enum variables
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function count() : int
    {
        return \count(self::getConstants());
    }

    /**
     * Check if flag is set
     *
     * This only works for binary flags.
     *
     * @param int $flags        Set flags
     * @param int $checkForFlag Check if this flag is part of the set flags
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function hasFlag(int $flags, int $checkForFlag) : bool
    {
        return ($flags & $checkForFlag) === $checkForFlag;
    }
}
