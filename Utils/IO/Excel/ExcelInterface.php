<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils\IO\Excel
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Excel;

/**
 * Excel interface.
 *
 * @package    phpOMS\Utils\IO\Excel
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
interface ExcelInterface
{

    /**
     * Export Excel.
     *
     * @param string $path Path to export
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function exportExcel($path) : void;

    /**
     * Import Excel.
     *
     * @param string $path Path to import
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function importExcel($path) : void;
}
