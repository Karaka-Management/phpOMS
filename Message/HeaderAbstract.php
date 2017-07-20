<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Message;

/**
 * Response class.
 *
 * @category   Framework
 * @package    phpOMS\Response
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class HeaderAbstract
{
    /**
     * Responses.
     *
     * @var bool
     * @since 1.0.0
     */
    protected static $isLocked = false;

    /**
     * Set header.
     *
     * @param string $key       Header key
     * @param string $value     Header value
     * @param bool   $overwrite Overwrite if key already exists
     *
     * @since  1.0.0
     */
    abstract public function set(string $key, string $value, bool $overwrite = false);

    /**
     * Generate header based on status code.
     *
     * @param string $statusCode Status code
     *
     * @since  1.0.0
     */
    abstract public function generate(string $statusCode) /* : void */;

    /**
     * Get header by key.
     *
     * @param string $key Header key
     *
     * @return array
     *
     * @since  1.0.0
     */
    abstract public function get(string $key) : array;

    /**
     * Header has key?
     *
     * @param string $key Header key
     *
     * @return bool
     *
     * @since  1.0.0
     */
    abstract public function has(string $key) : bool;

    /**
     * Set header locked.
     *
     * @since  1.0.0
     */
    public static function lock() /* : void */
    {
        // todo: maybe pass session as member and make lock not static
        self::$isLocked = true;
    }

    /**
     * Is header locked?
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function isLocked() : bool
    {
        return self::$isLocked;
    }
}