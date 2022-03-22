<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Message;

use phpOMS\Localization\ISO639x1Enum;

/**
 * Response abstract class.
 *
 * @package phpOMS\Message
 * @license OMS License 1.0
 * @link    https://karaka.app
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
    protected array $response = [];

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
     * @param mixed $id Response ID
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function get(mixed $id) : mixed
    {
        return $this->response[$id] ?? null;
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
        // This is not working since the key contains :: from http://
        //$this->response = ArrayUtils::setArray((string) $key, $this->response, $response, ':', $overwrite);
        $this->response[$key] = $response;
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

        return $this->header->l11n->getLanguage();
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
