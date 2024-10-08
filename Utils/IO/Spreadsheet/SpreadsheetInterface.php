<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\IO\Spreadsheet
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Spreadsheet;

/**
 * Spreadsheet interface.
 *
 * @package phpOMS\Utils\IO\Spreadsheet
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface SpreadsheetInterface
{
    /**
     * Export Spreadsheet.
     *
     * @param string $path Path to export
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function exportSpreadsheet(string $path) : void;

    /**
     * Import Spreadsheet.
     *
     * @param string $path Path to import
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importSpreadsheet(string $path) : void;
}
