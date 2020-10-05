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

/**
 * Preloader class.
 *
 * @package phpOMS
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Preloader
{
    /**
     * Files and paths
     *
     * @var string[]
     * @since 1.0.0
     */
    private array $includes = [];

    /**
     * Ignored files and paths
     *
     * @var string[]
     * @since 1.0.0
     */
    private array $ignores = ['.', '..'];

    /**
     * Ignore a path or file from preloading
     *
     * @param string $path Path to prevent preloading
     *
     * @return Preloader
     *
     * @since 1.0.0
     */
    public function ignore(string $path) : self
    {
        $this->ignores[] = $path;

        return $this;
    }

    /**
     * Add a path to preload
     *
     * @param string $path Path to preload
     *
     * @return Preloader
     *
     * @since 1.0.0
     */
    public function includePath(string $path) : self
    {
        $this->includes[] = $path;

        return $this;
    }

    /**
     * Load paths
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function load() : void
    {
        foreach ($this->includes as $include) {
            if (\in_array($include, $this->ignores)) {
                continue;
            }

            if (\is_dir($include)) {
                $this->loadDir($include);
            } elseif (\is_file($include)) {
                $this->loadFile($include);
            }
        }
    }

    /**
     * Load directory paths
     *
     * @param string $path Path to load
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function loadDir(string $path) : void
    {
        $fh = \opendir($path);

        if ($fh === false) {
            return; // @codeCoverageIgnore
        }

        while ($file = \readdir($fh)) {
            if (\in_array($file, $this->ignores)) {
                continue;
            }

            if (\is_dir($path . '/' . $file)) {
                $this->loadDir($path . '/' . $file);
            } elseif (\is_file($path . '/' . $file)) {
                $this->loadFile($path . '/' . $file);
            }
        }

        \closedir($fh);
    }

    /**
     * Load file
     *
     * @param string $path Path to load
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function loadFile(string $path) : void
    {
        if (\in_array($path, $this->ignores)
            || \substr($path, -\strlen('.php')) !== '.php'
        ) {
            return;
        }

        require_once($path);
    }
}
