<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Utils\IO\Pdf
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Pdf;

/**
 * Pdf interface.
 *
 * @package phpOMS\Utils\IO\Pdf
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
interface PdfInterface
{
    /**
     * Export Pdf.
     *
     * @param string $path Path to export
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function exportPdf(string $path) : void;
}
