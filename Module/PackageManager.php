<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\Local\File;
use phpOMS\System\File\Local\LocalStorage;
use phpOMS\System\File\PathException;
use phpOMS\System\OperatingSystem;
use phpOMS\System\SystemType;
use phpOMS\Utils\IO\Zip\Zip;
use phpOMS\Utils\StringUtils;

/**
 * Package Manager model.
 *
 * The package manager is responsible for handling installation and update packages for modules, frameworks and resources.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class PackageManager
{
    /**
     * File path.
     *
     * @var   string
     * @since 1.0.0
     */
    private string $path = '';

    /**
     * Base path.
     *
     * @var   string
     * @since 1.0.0
     */
    private string $basePath = '';

    /**
     * Extract path.
     *
     * @var   string
     * @since 1.0.0
     */
    private string $extractPath = '';

    /**
     * Public key.
     *
     * @var   string
     * @since 1.0.0
     */
    private string $publicKey = '';

    /**
     * Info data.
     *
     * @var   array
     * @since 1.0.0
     */
    private array $info = [];

    /**
     * Constructor.
     *
     * @param string $path      Package source path e.g. path after download.
     * @param string $basePath  Path of the application
     * @param string $publicKey Public key
     *
     * @since 1.0.0
     */
    public function __construct(string $path, string $basePath, string $publicKey)
    {
        $this->path      = $path;
        $this->basePath  = \rtrim($basePath, '\\/');
        $this->publicKey = $publicKey;
    }

    /**
     * Extract package to temporary destination
     *
     * @param string $path Temporary extract path
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function extract(string $path) : void
    {
        $this->extractPath = \rtrim($path, '\\/');
        Zip::unpack($this->path, $this->extractPath);
    }

    /**
     * Load info data from path.
     *
     * @return void
     *
     * @throws PathException this exception is thrown in case the info file path doesn't exist
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function isValid() : bool
    {
        if (!\file_exists($this->extractPath . '/package.cert')) {
            return false;
        }

        $contents = \file_get_contents($this->extractPath . '/package.cert');
        return $this->authenticate($contents === false ? '' : $contents, $this->hashFiles());
    }

    /**
     * Hash array of files
     *
     * @return string Hash value of files
     *
     * @since 1.0.0
     */
    private function hashFiles() : string
    {
        $files = Directory::list($this->extractPath);
        $state = \sodium_crypto_generichash_init();

        foreach ($files as $file) {
            if ($file === 'package.cert' || \is_dir($this->extractPath . '/' . $file)) {
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
     * @since 1.0.0
     */
    public function install() : void
    {
        if (!$this->isValid()) {
            throw new \Exception();
        }

        foreach ($this->info['update'] as $steps) {
            foreach ($steps as $key => $components) {
                if (\method_exists($this, $key)) {
                    $this->{$key}($components);
                }
            }
        }
    }

    /**
     * Download files
     *
     * @param array $components Component data
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function download(array $components) : void
    {
        foreach ($components as $from => $to) {
            $fp = \fopen($this->basePath . '/' . $to, 'w+');
            $ch = \curl_init(\str_replace(' ','%20', $from));

            if ($ch === false || $fp === false) {
                continue; // @codeCoverageIgnore
            }

            \curl_setopt($ch, \CURLOPT_TIMEOUT, 50);
            \curl_setopt($ch, \CURLOPT_FILE, $fp);
            \curl_setopt($ch, \CURLOPT_FOLLOWLOCATION, true);

            \curl_exec($ch);

            \curl_close($ch);
            \fclose($fp);
        }
    }

    /**
     * Move files
     *
     * @param array $components Component data
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function move(array $components) : void
    {
        foreach ($components as $from => $to) {
            $fromPath = StringUtils::startsWith($from, '/Package/') ? $this->extractPath . '/' . \substr($from, 9) : $this->basePath . '/' . $from;
            $toPath   = StringUtils::startsWith($to, '/Package/') ? $this->extractPath . '/' . \substr($to, 9) : $this->basePath . '/' . $to;

            LocalStorage::move($fromPath, $toPath, true);
        }
    }

    /**
     * Copy files
     *
     * @param array $components Component data
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function copy(array $components) : void
    {
        foreach ($components as $from => $tos) {
            $fromPath = StringUtils::startsWith($from, '/Package/') ? $this->extractPath . '/' . \substr($from, 9) : $this->basePath . '/' . $from;

            foreach ($tos as $to) {
                $toPath = StringUtils::startsWith($to, '/Package/') ? $this->extractPath . '/' . \substr($to, 9) : $this->basePath . '/' . $to;

                LocalStorage::copy($fromPath, $toPath, true);
            }
        }
    }

    /**
     * Delete files
     *
     * @param array $components Component data
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function delete(array $components) : void
    {
        foreach ($components as $component) {
            $path = StringUtils::startsWith($component, '/Package/') ? $this->extractPath . '/' . \substr($component, 9) : $this->basePath . '/' . $component;
            LocalStorage::delete($path);
        }
    }

    /**
     * Execute commands
     *
     * @param array $components Component data
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function cmd(array $components) : void
    {
        foreach ($components as $component) {
            $cmd  = '';
            $path = StringUtils::startsWith($component, '/Package/') ? $this->extractPath . '/' . \substr($component, 9) : $this->basePath . '/' . $component;

            if (StringUtils::endsWith($component, '.php')) {
                // todo: maybe add a guessing method to find php path if it isn't available in the environment see Repository.php for git api
                $cmd = 'php ' . $path;
            } elseif (StringUtils::endsWith($component, '.sh') && OperatingSystem::getSystem() === SystemType::LINUX && \is_executable($path)) {
                $cmd = $path;
            } elseif (StringUtils::endsWith($component, '.batch') && OperatingSystem::getSystem() === SystemType::WIN && \is_executable($path)) {
                $cmd = $path;
            }

            if ($cmd !== '') {
                $pipes    = [];
                $resource = \proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, $this->extractPath);

                foreach ($pipes as $pipe) {
                    \fclose($pipe);
                }

                \proc_close($resource);
            }
        }
    }

    /**
     * Cleanup after installation
     *
     * @return void
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
