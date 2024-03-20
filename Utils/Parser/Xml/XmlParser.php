<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\Parser\Xml
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Xml;

/**
 * Xml parser class.
 *
 * @package phpOMS\Utils\Parser\Xml
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class XmlParser
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
     * Xml to string
     *
     * @param string $path Path
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function parseXml(string $path, string $output = 'xml', string $xpath = '') : string
    {
        $doc                     = new \DOMDocument();
        $doc->preserveWhiteSpace = true;
        $doc->formatOutput       = true;

        $xml = \file_get_contents($path);
        if ($xml === false || $xml === null) {
            return '';
        }

        $xml = \preg_replace(
            ['~<style.*?</style>~', '~<script.*?</script>~'],
            ['', ''],
            $xml
        );

        if ($xml === null) {
            return '';
        }

        $result = $doc->loadXML($xml);
        if ($result === false) {
            return '';
        }

        if (empty($xpath)) {
            $result = $doc->saveHTML();

            return $result === false ? '' : $result;
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
