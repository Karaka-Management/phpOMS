<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\System\File\PathException;
use phpOMS\Utils\ArrayUtils;
use phpOMS\System\File\Local\File;
use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\Local\LocalStorage;
use phpOMS\Utils\IO\Zip\Zip;

/**
 * Account group class.
 *
 * @category   Framework
 * @package    phpOMS\Account
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class PackageManager
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
     * Info data.
     *
     * @var array
     * @since 1.0.0
     */
    private $info = [];

    /**
     * Object constructor.
     *
     * @param string $path Package path
     * @param string $basePath Base Path of the application
     *
     * @since  1.0.0
     */
    public function __construct(string $path, string $basePath) 
    {
        $this->path = $path;
        $this->basePath = $basePath;
    }

    public function extract(string $path) : bool
    {
        $this->extractPath = $path;
        Zip::unpack($this->path, $this->extractPath);
    }

    /**
     * Load info data from path.
     *
     * @return void
     *
     * @throws PathException This exception is thrown in case the info file path doesn't exist.
     *
     * @since  1.0.0
     */
    public function load() /* : void */
    {
        if(!file_exists($this->extractPath)) {
            throw new PathException($this->extractPath);
        }

        $this->info = json_decode(file_get_contents($this->extractPath . '/info.json'), true);
    }

    public function isValid() : bool
    {
        return $this->authenticate(file_get_contents($this->extractPath . '/package.cert'), $this->hashFiles());
    }

    private function hashFiles(array $files) : string
    {
        $files = Directory::list($this->extractPath . '/package');
        $state = \sodium_crypto_generichash_init();

        foreach($files as $file) {
            if($file === 'package.cert') {
                continue; 
            }

            \sodium_crypto_generichash_update($state, file_get_contents($this->extractPath . '/package/' . $file));
        }

        return \sodium_crypto_generichash_final();
    }

    public function install() /* : void */
    {
        if(!$this->isValid()) {
            throw new \Exception();
        }

        foreach($this->info as $key => $components) {
            if(function_exists($this->{$key})) {
                $this->{$key}($components);
            }
        }
    }

    private function move($components)
    {
        foreach($components as $component) {
            LocalStorage::move($this->basePath . '/' . $component['from'], $this->basePath . '/' . $component['to'], true);
        }
    }

    private function copy($components)
    {
        foreach($components as $component) {
            if(StringUtils::startsWith($component['from'], 'Package/')) {
                LocalStorage::copy($this->path . '/' . $component['from'], $this->basePath . '/' . $component['to'], true);
            } else {
                LocalStorage::copy($this->basePath . '/' . $component['from'], $this->basePath . '/' . $component['to'], true);
            }
        }
    }

    private function delete($components)
    {
        foreach($components as $component) {
            LocalStorage::delete($this->basePath . '/' . $component);
        }
    }

    private function execute($components) 
    {
        foreach($components as $component) {
            include $this->basePath . '/' . $component;
        }
    }

    public function cleanup() 
    {
        File::delete($this->path);
        Directory::delete($this->extractPath);
    }

    private function authenticate(string $signedHash, string $rawHash)
    {
        // https://3v4l.org/PN9Xl
        $publicKey = 'MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAjr73rerPRq3ZwWmrUKsN
        Bjg8Wy5lnyWu9HCRQz0fix6grz+NOOsr4m/jazB2knfdtn7fi5XifbIbmrNJY8e6
        musCJ0FTgJPVBqVk7XAFVSRe2gUaCPZrTPtfZ00C3cynjwTlxSdjNtU9N0ZAo17s
        VWghH8ki4T2d5Mg1erGOtMJzp5yw47UHUa+KbxUmUV25WMcRYyi7+itD2xANF2AE
        +PQZT1dSEU8++NI+zT6tXD/Orv5ikk0whoVqvo6duWejx5n5cpJB4EiMo4Q7epbw
        9uMo9uIKqgQ9y3KdT36GBQkBErFf1dhf8KYJBGYMhO1UJE11dY3XrA7Ij1+zK+ai
        duQHOc5EMClUGZQzCJAIU5lj4WEHQ4Lo0gop+fx9hzuBTDxdyOjWSJzkqyuWMkq3
        zEpRBay785iaglaue9XDLee58wY+toiGLBfXe73gsbDqDSOll+cQYNjrronVN7sU
        Dc2WyTIVW1Z8KFwK10D3SW0oEylCaGLtClyyihuW7JPu/8Or1Zjf87W82XTm31Fp
        YkRgoEMDtVHZq0N2eHpLz1L8zKyT0ogZYN5eH5VlGrPcpwbAludNKlgAJ0hrgED1
        9YsCBLwJQpFa4VZP7A5a/Qcw8EFAvNkgaPpBbAAtWoDbyOQsez6Jsdan/enfZ18+
        LL7qOB5oFFM/pKlTIeVS+UsCAwEAAQ==';
        $unsignedHash = \sodium_crypto_sign_open($signedHash, $publicKey);

        return $unsignedHash === $rawHash;
    }
}