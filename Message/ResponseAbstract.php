<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message;

use phpOMS\Localization\ISO639x1Enum;

/**
 * Response abstract class.
 *
 * @package phpOMS\Message
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class ResponseAbstract implements \JsonSerializable, MessageInterface
{
    /**
     * Responses.
     *
     * @var array
     * @since 1.0.0
     */
    public array $data = [];

    /**
     * Header.
     *
     * @var HeaderAbstract
     * @since 1.0.0
     */
    public HeaderAbstract $header;

    /**
     * Get response by ID.
     *
     * @param mixed $key Response ID
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function get(mixed $key, string $type = null) : mixed
    {
        if ($key === null) {
            return $this->data;
        }

        $key = \is_string($key) ? \mb_strtolower($key) : $key;
        if (!isset($this->data[$key])) {
            return null;
        }

        switch ($type) {
            case null:
                return $this->data[$key];
            case 'int':
                return (int) $this->data[$key];
            case 'string':
                return (string) $this->data[$key];
            case 'float':
                return (float) $this->data[$key];
            case 'bool':
                return (bool) $this->data[$key];
            case 'DateTime':
                return new \DateTime((string) $this->data[$key]);
            default:
                return $this->data[$key];
        }
    }

    /**
     * Get data.
     *
     * @param string $key Data key
     *
     * @return null|string
     *
     * @since 1.0.0
     */
    public function getDataString(string $key) : ?string
    {
        $key = \mb_strtolower($key);

        if (($this->data[$key] ?? '') === '') {
            return null;
        }

        return (string) $this->data[$key];
    }

    /**
     * Get data.
     *
     * @param string $key Data key
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    public function getDataArray(string $key) : ?array
    {
        $key = \mb_strtolower($key);

        if (($this->data[$key] ?? '') === '' || !\is_array($this->data[$key])) {
            return null;
        }

        return $this->data[$key];
    }

    /**
     * Get data.
     *
     * @param string $key Data key
     *
     * @return null|int
     *
     * @since 1.0.0
     */
    public function getDataInt(string $key) : ?int
    {
        $key = \mb_strtolower($key);

        if (($this->data[$key] ?? '') === '') {
            return null;
        }

        return (int) $this->data[$key];
    }

    /**
     * Get data.
     *
     * @param string $key Data key
     *
     * @return null|float
     *
     * @since 1.0.0
     */
    public function getDataFloat(string $key) : ?float
    {
        $key = \mb_strtolower($key);

        if (($this->data[$key] ?? '') === '') {
            return null;
        }

        return (float) $this->data[$key];
    }

    /**
     * Get data.
     *
     * @param string $key Data key
     *
     * @return null|bool
     *
     * @since 1.0.0
     */
    public function getDataBool(string $key) : ?bool
    {
        $key = \mb_strtolower($key);

        if (($this->data[$key] ?? '') === '') {
            return null;
        }

        return (bool) $this->data[$key];
    }

    /**
     * Get data.
     *
     * @param string $key Data key
     *
     * @return null|\DateTime
     *
     * @since 1.0.0
     */
    public function getDataDateTime(string $key) : ?\DateTime
    {
        $key = \mb_strtolower($key);

        return empty($this->data[$key] ?? null)
            ? null
            : new \DateTime((string) $this->data[$key]);
    }

    /**
     * Get data.
     *
     * @param string $key Data key
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getDataJson(string $key) : array
    {
        $key = \mb_strtolower($key);

        if (($this->data[$key] ?? '') === '') {
            return [];
        }

        $json = \json_decode($this->data[$key], true); /** @phpstan-ignore-line */

        return \is_array($json) ? $json : [];
    }

    /**
     * Get data.
     *
     * @param string $key   Data key
     * @param string $delim Data delimiter
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getDataList(string $key, string $delim = ',') : array
    {
        $key = \mb_strtolower($key);

        if (($this->data[$key] ?? '') === '') {
            return [];
        }

        /* @phpstan-ignore-next-line */
        $list = \explode($delim, $this->data[$key]);

        if ($list === false) {
            return []; // @codeCoverageIgnore
        }

        foreach ($list as $i => $e) {
            $list[$i] = \trim($e);
        }

        return $list;
    }

    /**
     * Get data based on wildcard.
     *
     * @param string $regex Regex data key
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getLike(string $regex) : array
    {
        $data = [];
        foreach ($this->data as $key => $value) {
            if (\preg_match('/' . $regex . '/', (string) $key) === 1) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    /**
     * Check if has data.
     *
     * The following empty values are considered as not set (null, '', 0)
     *
     * @param string $key Data key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasData(string $key) : bool
    {
        $key = \mb_strtolower($key);

        return isset($this->data[$key])
            && $this->data[$key] !== ''
            && $this->data[$key] !== null;
    }

    /**
     * Add response.
     *
     * @param mixed $key       Response id
     * @param mixed $response  Response to add
     * @param bool  $overwrite Overwrite
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function set(mixed $key, mixed $response, bool $overwrite = false) : void
    {
        $this->data[$key] = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }

    /**
     * Generate response array from views.
     *
     * @return array
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    abstract public function toArray() : array;

    /**
     * Get response language.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getLanguage() : string
    {
        if (!isset($this->header)) {
            return ISO639x1Enum::_EN;
        }

        return $this->header->l11n->language;
    }

    /**
     * Get response body.
     *
     * @param bool $optimize Optimize response / minify
     *
     * @return string
     *
     * @since 1.0.0
     */
    abstract public function getBody(bool $optimize = false) : string;
}
