<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Formatter
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Formatter;

/**
 * Html code formatter
 *
 * @package phpOMS\Utils\Formatter
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class HtmlFormatter
{
    /**
     * Format html code
     *
     * @param string $text Html code
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function format(string $text) : string
    {
        $dom = new \DOMDocument();

        $dom->loadHTML($text);

        $dom->preserveWhiteSpace = false;
        $dom->formatOutput       = true;

        $formatted = $dom->saveXML($dom->documentElement);

        return $formatted === false ? '' : $formatted;
    }
}
