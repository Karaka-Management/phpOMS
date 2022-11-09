<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\System\File\PathException;
use phpOMS\Utils\ArrayUtils;

/**
 * ModuleInfo class.
 *
 * Handling the info files for modules
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class ModuleInfo
{
    /**
     * File path.
     *
     * @var string
     * @since 1.0.0
     */
    private string $path = '';

    /**
     * Info data.
     *
     * @var array{name:array{id:int, internal:string, external:string}, category:string, vision:string, requirements:array, creator:array{name:string, website:string}, description:string, directory:string, dependencies:array<string, string>, providing:array<string, string>, load:array<int, array{pid:string[], type:int, for:string, file:string, from:string}>}|array
     * @since 1.0.0
     */
    private array $info = [];

    /**
     * Object constructor.
     *
     * @param string $path Info file path
     *
     * @since 1.0.0
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Get info path
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPath() : string
    {
        return $this->path;
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
        if (!\is_file($this->path)) {
            throw new PathException($this->path);
        }

        $contents = \file_get_contents($this->path);

        /** @var array{name:array{id:int, internal:string, external:string}, category:string, vision:string, requirements:array, creator:array{name:string, website:string}, description:string, directory:string, dependencies:array<string, string>, providing:array<string, string>, load:array<int, array{pid:string[], type:int, for:string, file:string, from:string}>} $info */
        $info       = \json_decode($contents === false ? '[]' : $contents, true);
        $this->info = $info === false ? [] : $info;
    }

    /**
     * Update info file
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function update() : void
    {
        if (!\is_file($this->path)) {
            throw new PathException($this->path);
        }

        \file_put_contents($this->path, \json_encode($this->info, \JSON_PRETTY_PRINT));
    }

    /**
     * Set data
     *
     * @param string $path  Value path
     * @param mixed  $data  Scalar or array of data to set
     * @param string $delim Delimiter of path
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function set(string $path, $data, string $delim = '/') : void
    {
        if (!\is_scalar($data) && !\is_array($data) && !($data instanceof \JsonSerializable)) {
            throw new \InvalidArgumentException('Type of $data "' . \gettype($data) . '" is not supported.');
        }

        ArrayUtils::setArray($path, $this->info, $data, $delim, true);
    }

    /**
     * Get info data.
     *
     * @return array{name:array{id:int, internal:string, external:string}, category:string, vision:string, requirements:array, creator:array{name:string, website:string}, description:string, directory:string, dependencies:array<string, string>, providing:array<string, string>, load:array<int, array{pid:string[], type:int, for:string, file:string, from:string}>}|array
     *
     * @since 1.0.0
     */
    public function get() : array
    {
        return $this->info;
    }

    /**
     * Get info data.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->info['name']['id'] ?? 0;
    }

    /**
     * Get info data.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getInternalName() : string
    {
        return $this->info['name']['internal'] ?? '';
    }

    /**
     * Get info data.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getExternalName() : string
    {
        return $this->info['name']['external'] ?? '';
    }

    /**
     * Get info data.
     *
     * @return array<string, string>
     *
     * @since 1.0.0
     */
    public function getDependencies() : array
    {
        return $this->info['dependencies'] ?? [];
    }

    /**
     * Get info data.
     *
     * @return array<string, string>
     *
     * @since 1.0.0
     */
    public function getProviding() : array
    {
        return $this->info['providing'] ?? [];
    }

    /**
     * Get info data.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getDirectory() : string
    {
        return $this->info['directory'] ?? '';
    }

    /**
     * Get info category.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCategory() : string
    {
        return $this->info['category'] ?? '';
    }

    /**
     * Get info data.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getVersion() : string
    {
        return $this->info['version'] ?? '';
    }

    /**
     * Get info data.
     *
     * @return array<array{pid:string[], type:int, for:string, file:string, from:string}>
     *
     * @since 1.0.0
     */
    public function getLoad() : array
    {
        return $this->info['load'] ?? [];
    }
}
