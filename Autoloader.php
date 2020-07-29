<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS;

\spl_autoload_register('\phpOMS\Autoloader::defaultAutoloader');

/**
 * Autoloader class.
 *
 * @package phpOMS
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Autoloader
{
    /**
     * Base paths for autoloading
     *
     * @var string[]
     * @since 1.0.0
     */
    private static $paths = [
        __DIR__ . '/../',
        __DIR__ . '/../../',
    ];

    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Add base path for autoloading
     *
     * @param string $path Absolute base path with / at the end
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function addPath(string $path) : void
    {
        self::$paths[] = $path;
    }

    /**
     * Loading classes by namespace + class name.
     *
     * @param string $class Class path
     *
     * @example Autoloader::defaultAutoloader('\phpOMS\Autoloader') // void
     *
     * @return void
     *
     * @throws AutoloadException Throws this exception if the class to autoload doesn't exist. This could also be related to a wrong namespace/file path correlation.
     *
     * @since 1.0.0
     */
    public static function defaultAutoloader(string $class) : void
    {
        $class = \ltrim($class, '\\');
        $class = \str_replace(['_', '\\'], '/', $class);

        foreach (self::$paths as $path) {
            if (\file_exists($file = $path . $class . '.php')) {
                include $file;

                return;
            }
        }
    }

    /**
     * Check if class exists.
     *
     * @param string $class Class path
     *
     * @example Autoloader::exists('\phpOMS\Autoloader') // true
     *
     * @return bool Returns true if the namespace/class exists, otherwise false
     *
     * @since 1.0.0
     */
    public static function exists(string $class) : bool
    {
        $class = \ltrim($class, '\\');
        $class = \str_replace(['_', '\\'], '/', $class);

        foreach (self::$paths as $path) {
            if (\file_exists($path . $class . '.php')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Invalidate a already loaded file
     *
     * IMPORTANT: This does not reload an already loaded file
     *
     * @param string $class Class to invalidate
     *
     * @return bool
     *
     * @since 1.0.0
     * @todo Find a way to re-load aready loaded files. This can be important for changed scripts
     */
    public static function invalidate(string $class) : bool
    {
        if (!\extension_loaded('opcache')
            || !\opcache_is_script_cached(__DIR__ . '/../../Models/NewsArticleMapper.php')
        ) {
            return false;
        }

        \opcache_invalidate(__DIR__ . '/../../Models/NewsArticleMapper.php');
        \opcache_compile_file(__DIR__ . '/../../Models/NewsArticleMapper.php');

        return true;
    }
}
