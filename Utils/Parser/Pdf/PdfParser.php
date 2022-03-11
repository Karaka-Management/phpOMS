<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Utils\Parser\Pdf
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Pdf;

use phpOMS\Ai\Ocr\Tesseract\TesseractOcr;
use phpOMS\System\SystemUtils;
use phpOMS\Utils\StringUtils;

/**
 * Pdf parser class.
 *
 * @package phpOMS\Utils\Parser\Pdf
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class PdfParser
{
    /**
     * Pdf to text
     *
     * @param string $path Pdf path
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function pdf2text(string $path) : string
    {
        $text = '';
        $tmpDir = \sys_get_temp_dir();

        SystemUtils::runProc(
            '/usr/bin/pdftotext', '-layout '
                . \escapeshellarg($path) . ' '
                .  \escapeshellarg($out = \tempnam($tmpDir, 'pdf_'))
        );

        $text = \file_get_contents($out);
        \unlink($out);

        if (\strlen($text) < 256) {
            SystemUtils::runProc(
                '/usr/bin/pdftoppm',
                '-jpeg -r 300 '
                    . \escapeshellarg($path) . ' '
                    .  \escapeshellarg($out = \tempnam($tmpDir, 'pdf_'))
            );

            $files = \glob($out . '*');
            if ($files === false) {
                return $text;
            }

            foreach ($files as $file) {
                if (
                    !StringUtils::endsWith($file, '.jpg')
                    && !StringUtils::endsWith($file, '.png')
                    && !StringUtils::endsWith($file, '.gif')
                ) {
                    continue;
                }

                /* Too slow
                Thresholding::integralThresholding($file, $file);
                Skew::autoRotate($file, $file, 10);
                */

                SystemUtils::runProc(
                    __DIR__ . '/../../../cOMS/Tools/InvoicePreprocessing/App',
                    \escapeshellarg($file) . ' '
                        .  \escapeshellarg($file)
                );

                $ocr = new TesseractOcr();
                $text = $ocr->parseImage($file);

                \unlink($file);
            }
        }

        return $text;
    }
}
