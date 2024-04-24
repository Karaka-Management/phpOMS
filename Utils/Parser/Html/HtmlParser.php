<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\Parser\Html
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Html;

/**
 * Html parser class.
 *
 * @package phpOMS\Utils\Parser\Html
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class HtmlParser
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
     * Html to string
     *
     * @param string $path Path
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function parseHtml(string $path, string $output = 'html', string $xpath = '') : string
    {
        $doc = new \DOMDocument();

        $html = \file_get_contents($path);
        if ($html === false) {
            return '';
        }

        $html = \preg_replace(
            ['~<style.*?</style>~', '~<script.*?</script>~'],
            ['', ''],
            $html
        );

        $doc->loadHTMLFile($path);

        if (empty($xpath)) {
            $body = $doc->getElementsByTagName('body');
            $node = $body->item(0);

            return empty($node->textContent) ? '' : $node->textContent;
        }

        $content  = '';
        $xNode    = new \DOMXpath($doc);
        $elements = $xNode->query($xpath);

        if ($elements === false) {
            return $content;
        }

        foreach ($elements as $element) {
            $nodes = $element->childNodes;

            foreach ($nodes as $node) {
                $content .= $node->textContent . "\n";
            }
        }

        return $content;
    }
}
