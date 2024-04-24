<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\Parser\Document
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Document;

use PhpOffice\PhpWord\Writer\PDF\AbstractRenderer;
use PhpOffice\PhpWord\Writer\WriterInterface;

/**
 * Save word document
 *
 * @package phpOMS\Utils\Parser\Document
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class DocumentWriter extends AbstractRenderer implements WriterInterface
{
    /**
     * Save PhpWord to file.
     *
     * @param string $filename Name of the file to save as
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function toPdfString($filename = null) : string
    {
        //  PDF settings
        $paperSize   = \strtoupper('A4');
        $orientation = \strtoupper('portrait');

        //  Create PDF
        $pdf = new \Mpdf\Mpdf();
        $pdf->_setPageSize($paperSize, $orientation);
        $pdf->addPage($orientation);

        // Write document properties
        $phpWord  = $this->getPhpWord();
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
