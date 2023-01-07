<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Parser\Presentation
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
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
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class PresentationParser
{
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
        }

        return '';
    }
}
