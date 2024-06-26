<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
abstract class EnumArray
{
    /**
     * Constants.
     *
     * @var array
     * @since 1.0.0
     */
    protected static array $constants = [];

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
        return isset(static::$constants[$name]);
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
        return static::$constants;
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
     * @since 1.0.0
     */
    public static function isValidValue(mixed $value) : bool
    {
        return \in_array($value, static::$constants, true);
    }

    /**
     * Get enum value by name.
     *
     * @param mixed $key Key to look for
     *
     * @return mixed
     *
     * @throws \OutOfBoundsException
     *
     * @since 1.0.0
     */
    public static function get(mixed $key) : mixed
    {
        if (!isset(static::$constants[$key])) {
            throw new \OutOfBoundsException('Key "' . $key . '" is not valid.');
        }

        return static::$constants[$key];
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
        return \count(static::$constants);
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
        return static::$constants[\array_rand(static::$constants, 1)];
    }
}
