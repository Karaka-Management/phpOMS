<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\Parser\Presentation
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Presentation;

use PhpOffice\PhpPresentation\IOFactory;

/**
 * Presentation parser class.
 *
 * @package phpOMS\Utils\Parser\Presentation
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class PresentationParser
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
     * Presentation to string
     *
     * @param string $path Path
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function parsePresentation(string $path, string $output = 'html') : string
    {
        if ($output === 'html') {
            // $pptReader = IOFactory::createReader('PowerPoint2007');
            // $pptReader->load(...);
            $presentation = IOFactory::load($path);

            $oTree = new PresentationWriter($presentation);

            return $oTree->renderHtml();
        } elseif ($output === 'txt') {
            $presentation = IOFactory::load($path);
            $oTree        = new PresentationWriter($presentation);
            $html         = $oTree->renderHtml();

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
