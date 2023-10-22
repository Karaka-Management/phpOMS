<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Parser\Document
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Document;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Document parser class.
 *
 * @package phpOMS\Utils\Parser\Document
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class DocumentParser
{
    /**
     * Document to string
     *
     * @param string $path Path
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function parseDocument(string $path, string $output = 'html') : string
    {
        $doc = IOFactory::load($path);

        if ($output === 'html') {
            $writer = new HTML($doc);

            return $writer->getContent();
        } elseif ($output === 'pdf') {
            $writer = new DocumentWriter($doc);

            return $writer->toPdfString();
        }

        return '';
    }
}
