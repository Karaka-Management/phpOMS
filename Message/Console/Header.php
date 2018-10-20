<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Message\Http
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Message\Console;

use phpOMS\Message\HeaderAbstract;
use phpOMS\DataStorage\LockException;

/**
 * Response class.
 *
 * @package    phpOMS\Message\Http
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class Header extends HeaderAbstract
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
    private $header = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->set('Content-Type', 'text/html; charset=utf-8');
        parent::__construct();
    }

    /**
     * Set header.
     *
     * @param string $key       Header key (case insensitive)
     * @param string $header    Header value
     * @param bool   $overwrite Overwrite if already existing
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function set(string $key, string $header, bool $overwrite = false) : bool
    {
        if (self::$isLocked) {
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
     * @param mixed $key Header key
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function remove($key) : bool
    {
        if (self::$isLocked) {
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
     * @param string $key Header key
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function get(string $key) : array
    {
        return $this->header[strtolower($key)] ?? [];
    }

    /**
     * Check if header is defined.
     *
     * @param string $key Header key
     *
     * @return bool
     *
     * @since  1.0.0
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
        switch ($code) {
            default:
                $this->generate500();
        }
    }

    /**
     * Generate predefined header.
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function generate500() : void
    {
    }
}
