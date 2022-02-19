<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Utils\IO\Csv
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Csv;

/**
 * Cvs interface.
 *
 * @package phpOMS\Utils\IO\Csv
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
interface CsvInterface
{
    /**
     * Export Csv.
     *
     * @param string $path Path to export
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function exportCsv(string $path) : void;

    /**
     * Import Csv.
     *
     * @param string $path Path to import
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function importCsv(string $path) : void;
}
