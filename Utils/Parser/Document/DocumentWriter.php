<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Parser\Document
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Document;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\WriterInterface;

class DocumentWriter extends AbstractRenderer implements WriterInterface
{
    /**
     * Save PhpWord to file.
     *
     * @param string $filename Name of the file to save as
     */
    public function toPdfString($filename = null): void
    {
        //  PDF settings
        $paperSize = strtoupper('A4');
        $orientation = strtoupper('portrait');

        //  Create PDF
        $pdf = $pdf = new \Mpdf\Mpdf();
        $pdf->_setPageSize($paperSize, $orientation);
        $pdf->addPage($orientation);

        // Write document properties
        $phpWord = $this->getPhpWord();
        $docProps = $phpWord->getDocInfo();
        $pdf->setTitle($docProps->getTitle());
        $pdf->setAuthor($docProps->getCreator());
        $pdf->setSubject($docProps->getSubject());
        $pdf->setKeywords($docProps->getKeywords());
        $pdf->setCreator($docProps->getCreator());

        $pdf->writeHTML($this->getContent());

        //  Write to file
        return $pdf->output($filename, 'S');
    }
}
