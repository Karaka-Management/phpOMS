<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Parser\Spreadsheet
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;

class SpreadsheetWriter extends Pdf
{
    /** @var bool */
    protected $isMPdf = true;

    /**
     * Save Spreadsheet to file.
     *
     * @param string $filename Name of the file to save as
     */
    public function toPdfString(): string
    {
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
            'margin-left'   => $this->inchesToMm($this->spreadsheet->getActiveSheet()->getPageMargins()->getLeft()),
            'margin-right'  => $this->inchesToMm($this->spreadsheet->getActiveSheet()->getPageMargins()->getRight()),
            'margin-top'    => $this->inchesToMm($this->spreadsheet->getActiveSheet()->getPageMargins()->getTop()),
            'margin-bottom' => $this->inchesToMm($this->spreadsheet->getActiveSheet()->getPageMargins()->getBottom()),
        ]);

        //  Document info
        $pdf->SetTitle($this->spreadsheet->getProperties()->getTitle());
        $pdf->SetAuthor($this->spreadsheet->getProperties()->getCreator());
        $pdf->SetSubject($this->spreadsheet->getProperties()->getSubject());
        $pdf->SetKeywords($this->spreadsheet->getProperties()->getKeywords());
        $pdf->SetCreator($this->spreadsheet->getProperties()->getCreator());

        $html         = $this->generateHTMLAll();
        $bodyLocation = strpos($html, Html::BODY_LINE);

        // Make sure first data presented to Mpdf includes body tag
        //   so that Mpdf doesn't parse it as content. Issue 2432.
        if ($bodyLocation !== false) {
            $bodyLocation += strlen(Html::BODY_LINE);
            $pdf->WriteHTML(substr($html, 0, $bodyLocation));
            $html = substr($html, $bodyLocation);
        }

        foreach (\array_chunk(\explode(PHP_EOL, $html), 1000) as $lines) {
            $pdf->WriteHTML(\implode(PHP_EOL, $lines));
        }

        $html = $pdf->Output('', 'S');

        parent::restoreStateAfterSave();

        return $html;
    }

    /**
     * Convert inches to mm.
     *
     * @param float $inches
     *
     * @return float
     */
    private function inchesToMm($inches)
    {
        return $inches * 25.4;
    }
}
