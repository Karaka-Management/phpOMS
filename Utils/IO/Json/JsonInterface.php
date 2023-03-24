<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\IO\Json
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Json;

/**
 * Cvs interface.
 *
 * @package phpOMS\Utils\IO\Json
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface JsonInterface
{
    /**
     * Export Json.
     *
     * @param string $path Path to export
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function exportJson(string $path) : void;

    /**
     * Import Json.
     *
     * @param string $path Path to import
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importJson(string $path) : void;
}
