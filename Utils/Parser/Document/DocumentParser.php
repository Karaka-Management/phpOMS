<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\Parser\Document
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
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
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class DocumentParser
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

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
        } elseif ($output === 'txt') {
            $writer = new HTML($doc);
            $html   = $writer->getContent();

            $doc  = new \DOMDocument();
            $html = \preg_replace(
                ['~<style.*?</style>~', '~<script.*?</script>~'],
                ['', ''],
                $html
            );

            $doc->loadHTMLFile($path);

            $body = $doc->getElementsByTagName('body');
            $node = $body->item(0);

            return empty($node->textContent) ? '' : $node->textContent;
        }

        return '';
    }
}
