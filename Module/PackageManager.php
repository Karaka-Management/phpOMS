<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Module
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\Local\File;
use phpOMS\System\File\Local\LocalStorage;
use phpOMS\System\File\PathException;
use phpOMS\Utils\IO\Zip\Zip;
use phpOMS\Utils\StringUtils;

/**
 * Package Manager model.
 *
 * The package manager is responsible for handling installation and update packages for modules, frameworks and resources.
 *
 * @package    phpOMS\Module
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class PackageManager
{
    /**
     * File path.
     *
     * @var string
     * @since 1.0.0
     */
    private $path = '';

    /**
     * Base path.
     *
     * @var string
     * @since 1.0.0
     */
    private $basePath = '';

    /**
     * Extract path.
     *
     * @var string
     * @since 1.0.0
     */
    private $extractPath = '';

    /**
     * Public key.
     *
     * @var string
     * @since 1.0.0
     */
    private $publicKey = '';

    /**
     * Info data.
     *
     * @var array
     * @since 1.0.0
     */
    private $info = [];

    /**
     * Constructor.
     *
     * @param string $path      Package source path e.g. path after download.
     * @param string $basePath  Path of the application
     * @param string $publicKey Public key
     *
     * @since  1.0.0
     */
    public function __construct(string $path, string $basePath, string $publicKey)
    {
        $this->path      = $path;
        $this->basePath  = $basePath; // todo: maybe remove from here because stupid
        $this->publicKey = $publicKey;
    }

    /**
     * Extract package to temporary destination
     *
     * @param string $path Temporary extract path
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function extract(string $path) : void
    {
        $this->extractPath = $path;
        Zip::unpack($this->path, $this->extractPath);
    }

    /**
     * Load info data from path.
     *
     * @return void
     *
     * @throws PathException this exception is thrown in case the info file path doesn't exist
     *
     * @since  1.0.0
     */
    public function load() : void
    {
        if (!\file_exists($this->extractPath)) {
            throw new PathException($this->extractPath);
        }

        $contents   = \file_get_contents($this->extractPath . '/info.json');
        $info       = \json_decode($contents === false ? '[]' : $contents, true);
        $this->info = $info === false ? [] : $info;
    }

    /**
     * Validate package integrity
     *
     * @return bool Returns true if the package is authentic, false otherwise
     *
     * @since  1.0.0
     */
    public function isValid() : bool
    {
        $contents = \file_get_contents($this->extractPath . '/package.cert');
        return $this->authenticate($contents === false ? '' : $contents, $this->hashFiles());
    }

    /**
     * Hash array of files
     *
     * @return string Hash value of files
     *
     * @since  1.0.0
     */
    private function hashFiles() : string
    {
        $files = Directory::list($this->extractPath);
        $state = \sodium_crypto_generichash_init();

        foreach ($files as $file) {
            if ($file === 'package.cert') {
                continue;
            }

            $contents = \file_get_contents($this->extractPath . '/' . $file);
            if ($contents === false) {
                throw new \Exception();
            }

            \sodium_crypto_generichash_update($state, $contents);
        }

        return \sodium_crypto_generichash_final($state);
    }

    /**
     * Install package
     *
     * @return void
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public function install() : void
    {
        if (!$this->isValid()) {
            throw new \Exception();
        }

        foreach ($this->info as $key => $components) {
            if (\function_exists($this->{$key})) {
                $this->{$key}($components);
            }
        }
    }

    /**
     * Move files
     *
     * @param mixed $components Component data
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function move($components) : void
    {
        foreach ($components as $component) {
            LocalStorage::move($this->basePath . '/' . $component['from'], $this->basePath . '/' . $component['to'], true);
        }
    }

    /**
     * Copy files
     *
     * @param mixed $components Component data
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function copy($components) : void
    {
        foreach ($components as $component) {
            if (StringUtils::startsWith($component['from'], 'Package/')) {
                LocalStorage::copy($this->path . '/' . $component['from'], $this->basePath . '/' . $component['to'], true);
            } else {
                LocalStorage::copy($this->basePath . '/' . $component['from'], $this->basePath . '/' . $component['to'], true);
            }
        }
    }

    /**
     * Delete files
     *
     * @param mixed $components Component data
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function delete($components) : void
    {
        foreach ($components as $component) {
            LocalStorage::delete($this->basePath . '/' . $component);
        }
    }

    /**
     * Execute commands
     *
     * @param mixed $components Component data
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function execute($components) : void
    {
        foreach ($components as $component) {
            include $this->basePath . '/' . $component;
        }
    }

    /**
     * Cleanup after installation
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function cleanup() : void
    {
        File::delete($this->path);
        Directory::delete($this->extractPath);
    }

    /**
     * Authenticate package
     *
     * @param string $signedHash Hash to authenticate
     * @param string $rawHash    Hash to compare against
     *
     * @return bool
     *
     * @since  1.0.0
     */
    private function authenticate(string $signedHash, string $rawHash) : bool
    {
        try {
            return \sodium_crypto_sign_verify_detached($signedHash, $rawHash, $this->publicKey);
        } catch(\Throwable $t) {
            return false;
        }
    }
}
