<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    Tests\PHPUnit
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace Tests\PHPUnit;

\spl_autoload_register('\Tests\PHPUnit\Autoloader::defaultAutoloader');

/**
 * Autoloader class.
 *
 * @package    Tests\PHPUnit
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class Autoloader
{

    /**
     * Loading classes by namespace + class name.
     *
     * @param string $class Class path
     *
     * @example Autoloader::defaultAutoloader('\Tests\PHPUnit\Autoloader') // void
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function defaultAutoloader(string $class) : void
    {
        $class = \ltrim($class, '\\');
        $class = \str_replace(['_', '\\'], '/', $class);

        if (!\file_exists($path = __DIR__ . '/../../' . $class . '.php')) {
            return;
        }

        /** @noinspection PhpIncludeInspection */
        include_once $path;
    }

    /**
     * Check if class exists.
     *
     * @param string $class Class path
     *
     * @example Autoloader::exists('\Tests\PHPUnit\Autoloader') // true
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function exists(string $class) : bool
    {
        $class = \ltrim($class, '\\');
        $class = \str_replace(['_', '\\'], '/', $class);

        return \file_exists(__DIR__ . '/../../' . $class . '.php');
    }
}
