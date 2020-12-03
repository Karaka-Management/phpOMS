<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Uri
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Uri;

/**
 * Uri interface.
 *
 * @property string $scheme Scheme
 * @property string $host Host
 * @property int $port Port
 * @property string $fragment Fragment
 * @property string $user User
 * @property string $pass Password
 *
 * @package phpOMS\Uri
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface UriInterface
{
    /**
     * Is uri valid?
     *
     * @param string $uri Uri string
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isValid(string $uri) : bool;

    /**
     * Get authority.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getAuthority() : string;

    /**
     * Get user info.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getUserInfo() : string;

    /**
     * Get path.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPath() : string;

    /**
     * Set path.
     *
     * @param string $path Path
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setPath(string $path) : void;

    /**
     * Get root path.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getRootPath() : string;

    /**
     * Set root path.
     *
     * @param string $root Root path
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setRootPath(string $root) : void;

    /**
     * Set path offset.
     *
     * This can be used if the uri path starts with elements which are not relevant later on.
     *
     * @param int $offset Path offset
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setPathOffset(int $offset = 0) : void;

    /**
     * Get path element.
     *
     * @param int $pos Position of the path
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPathElement(int $pos = 0) : string;

    /**
     * Get path elements.
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    public function getPathElements() : array;

    /**
     * Get query.
     *
     * @param string $key Query key
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getQuery(string $key = null) : string;

    /**
     * Get query array.
     *
     * @return array<string, string>
     *
     * @since 1.0.0
     */
    public function getQueryArray() : array;

    /**
     * Get uri.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function __toString();

    /**
     * Get base uri.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getBase() : string;

    /**
     * Get route representation of uri.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getRoute() : string;

    /**
     * Set uri.
     *
     * @param string $uri Uri
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function set(string $uri) : void;
}
