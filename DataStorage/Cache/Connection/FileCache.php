<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Cache\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException;
use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;
use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\Local\File;

/**
 * File cache.
 *
 * This implementation uses the hard drive as cache by saving data to the disc as text files.
 * The text files follow a defined strucuture which allows this implementation to parse the cached data.
 *
 * Allowed datatypes: null, int, bool, float, string, \DateTime, \JsonSerializable, \Serializable
 * File structure:
 *      data type (1 byte)
 *      delimiter (1 byte)
 *      expiration duration in seconds (1 - n bytes) (based on the file creation date)
 *      delimiter (1 byte)
 *      data (n bytes)
 *
 * @package phpOMS\DataStorage\Cache\Connection
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class FileCache extends ConnectionAbstract
{
    /**
     * {@inheritdoc}
     */
    protected string $type = CacheType::FILE;

    /**
     * Delimiter for cache meta data
     *
     * @var string
     * @since 1.0.0
     */
    private const DELIM = '$';

    /**
     * File path sanitizer
     *
     * @var string
     * @since 1.0.0
     */
    private const SANITIZE = '~';

    /**
     * Only cache if data is larger than threshold (0-100).
     *
     * @var int
     * @since 1.0.0
     */
    private int $threshold = 50;

    /**
     * Constructor
     *
     * @param string $path Cache path
     *
     * @since 1.0.0
     */
    public function __construct(string $path)
    {
        $this->connect([$path]);
    }

    /**
     * Connect to cache
     *
     * @param null|array{0:string} $data Cache data (path to cache directory)
     */
    public function connect(array $data = null) : void
    {
        $this->dbdata = $data;

        if (!Directory::exists($data[0])) {
            Directory::create($data[0], 0766, true);
        }

        if (\realpath($data[0]) === false) {
            $this->status = CacheStatus::FAILURE;
            throw new InvalidConnectionConfigException((string) \json_encode($this->dbdata));
        }

        $this->status = CacheStatus::OK;
        $this->con    = \realpath($data[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll() : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        \array_map('unlink', \glob($this->con . '/*'));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function stats() : array
    {
        if ($this->status !== CacheStatus::OK) {
            return [];
        }

        $stats           = [];
        $stats['status'] = $this->status;
        $stats['count']  = Directory::count($this->con);
        $stats['size']   = Directory::size($this->con);

        return $stats;
    }

    /**
     * {@inheritdoc}
     */
    public function getThreshold() : int
    {
        return $this->threshold;
    }

    /**
     * {@inheritdoc}
     */
    public function set(int | string $key, mixed $value, int $expire = -1) : void
    {
        if ($this->status !== CacheStatus::OK) {
            return;
        }

        $path = Directory::sanitize((string) $key, self::SANITIZE);

        $fp = \fopen($this->con . '/' . \trim($path, '/') . '.cache', 'w+');
        if (\flock($fp, \LOCK_EX)) {
            \ftruncate($fp, 0);
            \fwrite($fp, $this->build($value, $expire));
            \fflush($fp);
            \flock($fp, \LOCK_UN);
        }
        \fclose($fp);
    }

    /**
     * {@inheritdoc}
     */
    public function add(int | string $key, mixed $value, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $path = $this->getPath($key);

        if (!File::exists($path)) {
            $fp = \fopen($path, 'w+');
            if (\flock($fp, \LOCK_EX)) {
                \ftruncate($fp, 0);
                \fwrite($fp, $this->build($value, $expire));
                \fflush($fp);
                \flock($fp, \LOCK_UN);
            }
            \fclose($fp);

            return true;
        }

        return false;
    }

    /**
     * Removing all cache elements larger or equal to the expiration date. Call flushAll for removing persistent cache elements (expiration is negative) as well.
     *
     * @param mixed $value  Data to cache
     * @param int   $expire Expire date of the cached data
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function build(mixed $value, int $expire) : string
    {
        $type = $this->dataType($value);
        $raw  = $this->stringify($value, $type);

        return $type . self::DELIM . $expire . self::DELIM . $raw;
    }

    /**
     * Create string representation of data for storage
     *
     * @param mixed $value Value of the data
     * @param int   $type  Type of the cache data
     *
     * @return string
     *
     * @throws InvalidEnumValue This exception is thrown if an unsupported cache value type is used
     *
     * @since 1.0.0
     */
    private function stringify(mixed $value, int $type) : string
    {
        if ($type === CacheValueType::_INT || $type === CacheValueType::_STRING || $type === CacheValueType::_BOOL) {
            return (string) $value;
        } elseif ($type === CacheValueType::_FLOAT) {
            return \rtrim(\rtrim(\number_format($value, 5, '.', ''), '0'), '.');
        } elseif ($type === CacheValueType::_ARRAY) {
            return (string) \json_encode($value);
        } elseif ($type === CacheValueType::_SERIALIZABLE) {
            return \get_class($value) . self::DELIM . $value->serialize();
        } elseif ($type === CacheValueType::_JSONSERIALIZABLE) {
            return \get_class($value) . self::DELIM . ((string) \json_encode($value->jsonSerialize()));
        } elseif ($type === CacheValueType::_NULL) {
            return '';
        }

        throw new InvalidEnumValue($type); // @codeCoverageIgnore
    }

    /**
     * Get expire offset
     *
     * @param string $raw Raw data
     *
     * @return int
     *
     * @since 1.0.0
     */
    private function getExpire(string $raw) : int
    {
        $expireStart = (int) \strpos($raw, self::DELIM);
        $expireEnd   = (int) \strpos($raw, self::DELIM, $expireStart + 1);

        return (int) \substr($raw, $expireStart + 1, $expireEnd - ($expireStart + 1));
    }

    /**
     * {@inheritdoc}
     */
    public function get(int | string $key, int $expire = -1) : mixed
    {
        if ($this->status !== CacheStatus::OK) {
            return null;
        }

        $path = $this->getPath($key);
        if (!File::exists($path)) {
            return null;
        }

        $created = File::created($path)->getTimestamp();
        $now     = \time();

        if ($expire >= 0 && $created + $expire < $now) {
            return null;
        }

        $raw = \file_get_contents($path);
        if ($raw === false) {
            return null; // @codeCoverageIgnore
        }

        $type        = (int) $raw[0];
        $expireStart = (int) \strpos($raw, self::DELIM);
        $expireEnd   = (int) \strpos($raw, self::DELIM, $expireStart + 1);

        if ($expireStart < 0 || $expireEnd < 0) {
            return null; // @codeCoverageIgnore
        }

        $cacheExpire = \substr($raw, $expireStart + 1, $expireEnd - ($expireStart + 1));
        $cacheExpire = ($cacheExpire === -1) ? $created : (int) $cacheExpire;

        if ($cacheExpire >= 0 && $created + $cacheExpire + ($expire > 0 ? $expire : 0) < $now) {
            $this->delete($key);

            return null;
        }

        return $this->reverseValue($type, $raw, $expireEnd);
    }

    /**
     * Parse cached value
     *
     * @param int    $type      Cached value type
     * @param string $raw       Cached value
     * @param int    $expireEnd Value end position
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private function reverseValue(int $type, string $raw, int $expireEnd) : mixed
    {
        switch ($type) {
            case CacheValueType::_INT:
                return (int) \substr($raw, $expireEnd + 1);
            case CacheValueType::_FLOAT:
                return (float) \substr($raw, $expireEnd + 1);
            case CacheValueType::_BOOL:
                return (bool) \substr($raw, $expireEnd + 1);
            case CacheValueType::_STRING:
                return \substr($raw, $expireEnd + 1);
            case CacheValueType::_ARRAY:
                $array = \substr($raw, $expireEnd + 1);
                return \json_decode($array === false ? '[]' : $array, true);
            case CacheValueType::_NULL:
                return null;
            case CacheValueType::_JSONSERIALIZABLE:
                $namespaceStart = (int) \strpos($raw, self::DELIM, $expireEnd);
                $namespaceEnd   = (int) \strpos($raw, self::DELIM, $namespaceStart + 1);
                $namespace      = \substr($raw, $namespaceStart + 1, $namespaceEnd - $namespaceStart - 1);

                if ($namespace === false) {
                    return null; // @codeCoverageIgnore
                }

                return new $namespace();
            case CacheValueType::_SERIALIZABLE:
                $namespaceStart = (int) \strpos($raw, self::DELIM, $expireEnd);
                $namespaceEnd   = (int) \strpos($raw, self::DELIM, $namespaceStart + 1);
                $namespace      = \substr($raw, $namespaceStart + 1, $namespaceEnd - $namespaceStart - 1);

                if ($namespace === false) {
                    return null; // @codeCoverageIgnore
                }

                $obj = new $namespace();
                $obj->unserialize(\substr($raw, $namespaceEnd + 1));

                return $obj;
            default:
                return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int | string $key, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $path = $this->getPath($key);
        if (!File::exists($path)) {
            return true;
        }

        if ($expire < 0) {
            File::delete($path);

            return true;
        }

        if ($expire >= 0) {
            $created = File::created($path)->getTimestamp();
            $now     = \time();
            $raw     = \file_get_contents($path);

            if ($raw === false) {
                return false; // @codeCoverageIgnore
            }

            $cacheExpire = $this->getExpire($raw);
            $cacheExpire = ($cacheExpire === -1) ? $created : (int) $cacheExpire;

            if (($cacheExpire >= 0 && $created + $cacheExpire < $now)
                || ($cacheExpire >= 0 && \abs($now - $created) > $expire)
            ) {
                File::delete($path);

                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(int | string $key, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $path = $this->getPath($key);
        if (!File::exists($path)) {
            return false;
        }

        $created = File::created($path)->getTimestamp();
        $now     = \time();

        if ($expire >= 0 && $created + $expire < $now) {
            return false;
        }

        $raw = \file_get_contents($path);
        if ($raw === false) {
            return false; // @codeCoverageIgnore
        }

        $type        = (int) $raw[0];
        $expireStart = (int) \strpos($raw, self::DELIM);
        $expireEnd   = (int) \strpos($raw, self::DELIM, $expireStart + 1);

        if ($expireStart < 0 || $expireEnd < 0) {
            return false; // @codeCoverageIgnore
        }

        $cacheExpire = \substr($raw, $expireStart + 1, $expireEnd - ($expireStart + 1));
        $cacheExpire = ($cacheExpire === -1) ? $created : (int) $cacheExpire;

        if ($cacheExpire >= 0 && $created + $cacheExpire + ($expire > 0 ? $expire : 0) < $now) {
            File::delete($path);

            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function increment(int | string $key, int $value = 1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $path = $this->getPath($key);
        if (!File::exists($path)) {
            return false;
        }

        $created = File::created($path)->getTimestamp();
        $now     = \time();

        $raw = \file_get_contents($path);
        if ($raw === false) {
            return false; // @codeCoverageIgnore
        }

        $type        = (int) $raw[0];
        $expireStart = (int) \strpos($raw, self::DELIM);
        $expireEnd   = (int) \strpos($raw, self::DELIM, $expireStart + 1);

        if ($expireStart < 0 || $expireEnd < 0) {
            return false; // @codeCoverageIgnore
        }

        $cacheExpire = \substr($raw, $expireStart + 1, $expireEnd - ($expireStart + 1));
        $cacheExpire = ($cacheExpire === -1) ? $created : (int) $cacheExpire;

        $val = $this->reverseValue($type, $raw, $expireEnd);
        $this->set($key, $val + $value, $cacheExpire);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function decrement(int | string $key, int $value = 1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $path = $this->getPath($key);
        if (!File::exists($path)) {
            return false;
        }

        $created = File::created($path)->getTimestamp();
        $now     = \time();

        $raw = \file_get_contents($path);
        if ($raw === false) {
            return false; // @codeCoverageIgnore
        }

        $type        = (int) $raw[0];
        $expireStart = (int) \strpos($raw, self::DELIM);
        $expireEnd   = (int) \strpos($raw, self::DELIM, $expireStart + 1);

        if ($expireStart < 0 || $expireEnd < 0) {
            return false; // @codeCoverageIgnore
        }

        $cacheExpire = \substr($raw, $expireStart + 1, $expireEnd - ($expireStart + 1));
        $cacheExpire = ($cacheExpire === -1) ? $created : (int) $cacheExpire;

        $val = $this->reverseValue($type, $raw, $expireEnd);
        $this->set($key, $val - $value, $cacheExpire);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rename(int | string $old, int | string $new, int $expire = -1) : void
    {
        if ($this->status !== CacheStatus::OK) {
            return;
        }

        $value = $this->get($old);
        $this->set($new, $value, $expire);
        $this->delete($old);
    }

    /**
     * {@inheritdoc}
     */
    public function getLike(string $pattern, int $expire = -1) : array
    {
        if ($this->status !== CacheStatus::OK) {
            return [];
        }

        $files  = Directory::list($this->con . '/', $pattern . '\.cache', true);
        $values = [];

        foreach ($files as $path) {
            $path    = $this->con . '/' . $path;
            $created = File::created($path)->getTimestamp();
            $now     = \time();

            if ($expire >= 0 && $created + $expire < $now) {
                continue;
            }

            $raw = \file_get_contents($path);
            if ($raw === false) {
                continue; // @codeCoverageIgnore
            }

            $type        = (int) $raw[0];
            $expireStart = (int) \strpos($raw, self::DELIM);
            $expireEnd   = (int) \strpos($raw, self::DELIM, $expireStart + 1);

            if ($expireStart < 0 || $expireEnd < 0) {
                continue; // @codeCoverageIgnore
            }

            $cacheExpire = \substr($raw, $expireStart + 1, $expireEnd - ($expireStart + 1));
            $cacheExpire = ($cacheExpire === -1) ? $created : (int) $cacheExpire;

            if ($cacheExpire >= 0 && $created + $cacheExpire + ($expire > 0 ? $expire : 0) < $now) {
                File::delete($path);

                continue;
            }

            $values[] = $this->reverseValue($type, $raw, $expireEnd);
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteLike(string $pattern, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $files = Directory::list($this->con . '/', $pattern . '\.cache', true);

        foreach ($files as $path) {
            $path = $this->con . '/' . $path;

            if ($expire < 0) {
                File::delete($path);

                continue;
            }

            if ($expire >= 0) {
                $created = File::created($path)->getTimestamp();
                $now     = \time();
                $raw     = \file_get_contents($path);

                if ($raw === false) {
                    continue; // @codeCoverageIgnore
                }

                $cacheExpire = $this->getExpire($raw);
                $cacheExpire = ($cacheExpire === -1) ? $created : (int) $cacheExpire;

                if (($cacheExpire >= 0 && $created + $cacheExpire < $now)
                    || ($cacheExpire >= 0 && \abs($now - $created) > $expire)
                ) {
                    File::delete($path);

                    continue;
                }
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function updateExpire(int | string $key, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $value = $this->get($key, $expire);
        $this->delete($key);
        $this->set($key, $value, $expire);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function flush(int $expire = 0) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $dir = new Directory($this->con);
        $now = \time();

        foreach ($dir as $file) {
            if ($file instanceof File) {
                $created = $file->createdAt->getTimestamp();
                if (($expire >= 0 && $created + $expire < $now)
                    || ($expire < 0 && $created + $this->getExpire($file->getContent()) < $now)
                ) {
                    File::delete($file->getPath());
                }
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function replace(int | string $key, mixed $value, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $path = $this->getPath($key);

        if (File::exists($path)) {
            $fp = \fopen($path, 'w+');
            if (\flock($fp, \LOCK_EX)) {
                \ftruncate($fp, 0);
                \fwrite($fp, $this->build($value, $expire));
                \fflush($fp);
                \flock($fp, \LOCK_UN);
            }
            \fclose($fp);

            return true;
        }

        return false;
    }

    /**
     * Get cache path
     *
     * @param mixed $key Key for cached value
     *
     * @return string Path to cache file
     *
     * @since 1.0.0
     */
    private function getPath(int | string $key) : string
    {
        $path = Directory::sanitize((string) $key, self::SANITIZE);
        return $this->con . '/' . \trim($path, '/') . '.cache';
    }
}
