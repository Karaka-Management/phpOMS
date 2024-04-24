<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\Parser\Spreadsheet
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;

/**
 * Spreadsheet writer
 *
 * @package phpOMS\Utils\Parser\Spreadsheet
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class SpreadsheetWriter extends Pdf
{
    /**
     * Save Spreadsheet to file.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function toPdfString() : string
    {
        $this->isMPdf = true;

        $pdf = new \Mpdf\Mpdf();

        //  Check for paper size and page orientation
        $setup          = $this->spreadsheet->getSheet($this->getSheetIndex() ?? 0)->getPageSetup();
        $orientation    = $this->getOrientation() ?? $setup->getOrientation();
        $orientation    = ($orientation === PageSetup::ORIENTATION_LANDSCAPE) ? 'L' : 'P';
        $printPaperSize = $this->getPaperSize() ?? $setup->getPaperSize();
        $paperSize      = self::$paperSizes[$printPaperSize] ?? PageSetup::getPaperSizeDefault();

        $ortmp = $orientation;
        $pdf->_setPageSize($paperSize, $ortmp);
        $pdf->DefOrientation = $orientation;
        $pdf->AddPageByArray([
            'orientation'   => $orientation,
            'margin-left'   => $this->spreadsheet->getActiveSheet()->getPageMargins()->getLeft() * 25.4,
            'margin-right'  => $this->spreadsheet->getActiveSheet()->getPageMargins()->getRight() * 25.4,
            'margin-top'    => $this->spreadsheet->getActiveSheet()->getPageMargins()->getTop() * 25.4,
            'margin-bottom' => $this->spreadsheet->getActiveSheet()->getPageMargins()->getBottom() * 25.4,
        ]);

        //  Document info
        $pdf->SetTitle($this->spreadsheet->getProperties()->getTitle());
        $pdf->SetAuthor($this->spreadsheet->getProperties()->getCreator());
        $pdf->SetSubject($this->spreadsheet->getProperties()->getSubject());
        $pdf->SetKeywords($this->spreadsheet->getProperties()->getKeywords());
        $pdf->SetCreator($this->spreadsheet->getProperties()->getCreator());

        $html         = $this->generateHTMLAll();
        $bodyLocation = \strpos($html, Html::BODY_LINE);

        // Make sure first data presented to Mpdf includes body tag
        //   so that Mpdf doesn't parse it as content. Issue 2432.
        if ($bodyLocation !== false) {
            $bodyLocation += \strlen(Html::BODY_LINE);
            $pdf->WriteHTML(\substr($html, 0, $bodyLocation));
            $html = \substr($html, $bodyLocation);
        }

        foreach (\array_chunk(\explode(\PHP_EOL, $html), 1000) as $lines) {
            $pdf->WriteHTML(\implode(\PHP_EOL, $lines));
        }

        $html = $pdf->Output('', 'S');

        parent::restoreStateAfterSave();

        return $html;
    }
}
