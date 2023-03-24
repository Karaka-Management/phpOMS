<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\System\File
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System\File;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package phpOMS\System\File
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Storage
{
    /**
     * Registered storage.
     *
     * @var array<string, StorageAbstract|string|ContainerInterface>
     * @since 1.0.0
     */
    private static array $registered = [];

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
     * Get registred env instance.
     *
     * @param string $env Environment name
     *
     * @return StorageAbstract
     *
     * @throws \Exception Throws exception in case of invalid storage
     *
     * @since 1.0.0
     */
    public static function env(string $env = 'local') : StorageAbstract
    {
        if (isset(self::$registered[$env])) {
            if (\is_string(self::$registered[$env])) {
                /** @var StorageAbstract $instance */
                $instance               = new self::$registered[$env]();
                self::$registered[$env] = $instance;
            } elseif (self::$registered[$env] instanceof StorageAbstract
                || self::$registered[$env] instanceof ContainerInterface
            ) {
                /** @var StorageAbstract $instance */
                $instance = self::$registered[$env];
            } else {
                throw new \Exception('Invalid type');
            }
        } else {
            $stg = $env;
            $env = \ucfirst(\strtolower($env));
            /** @var StorageAbstract $env */
            $env = __NAMESPACE__ . '\\' . $env . '\\' . $env . 'Storage';

            /** @var StorageAbstract $instance */
            $instance = new $env();

            self::$registered[$stg] = $instance;
        }

        return $instance;
    }

    /**
     * Register storage environment.
     *
     * @param string                                    $name  Name of the environment
     * @param ContainerInterface|StorageAbstract|string $class Class to register. This can be either a namespace path, a anonymous class or storage implementation.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function register(string $name, $class) : bool
    {
        if (isset(self::$registered[$name])) {
            return false;
        }

        self::$registered[$name] = $class;

        return true;
    }
}
