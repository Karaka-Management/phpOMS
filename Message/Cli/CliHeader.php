<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Message\Cli
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Cli;

use phpOMS\Message\HeaderAbstract;

/**
 * Response class.
 *
 * @package phpOMS\Message\Cli
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class CliHeader extends HeaderAbstract
{
    /**
     * Protocol version.
     *
     * @var string
     * @since 1.0.0
     */
    private const VERSION = '1.0';

    /**
     * Header.
     *
     * @var string[][]
     * @since 1.0.0
     */
    private array $header = [];

    /**
     * Set header.
     *
     * @param string $key       Header key (case insensitive)
     * @param string $header    Header value
     * @param bool   $overwrite Overwrite if already existing
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function set(string $key, string $header, bool $overwrite = false) : bool
    {
        if ($this->isLocked) {
            return false;
        }

        $key = \strtolower($key);

        if (!$overwrite && isset($this->header[$key])) {
            return false;
        }

        unset($this->header[$key]);

        if (!isset($this->header[$key])) {
            $this->header[$key] = [];
        }

        $this->header[$key][] = $header;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion() : string
    {
        return self::VERSION;
    }

    /**
     * Remove header by ID.
     *
     * @param string $key Header key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function remove(string $key) : bool
    {
        if ($this->isLocked) {
            return false;
        }

        if (isset($this->header[$key])) {
            unset($this->header[$key]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getReasonPhrase() : string
    {
        $phrases = $this->get('Status');

        return empty($phrases) ? '' : $phrases[0];
    }

    /**
     * Get header by name.
     *
     * @param null|string $key Header key
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function get(?string $key = null) : array
    {
        return $key === null ? $this->header : ($this->header[\strtolower($key)] ?? []);
    }

    /**
     * Check if header is defined.
     *
     * @param string $key Header key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function has(string $key) : bool
    {
        return isset($this->header[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function generate(int $code) : void
    {
        exit($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTime() : int
    {
        return $this->timestamp;
    }
}
