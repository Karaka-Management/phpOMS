<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Parser\Pdf
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
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
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class PdfParser
{
    /**
     * PDFToText path.
     *
     * @var string
     * @var 1.0.0
     */
    public static string $pdftotext = '/usr/bin/pdftotext';

    /**
     * PDFToPPM path.
     *
     * @var string
     * @var 1.0.0
     */
    public static string $pdftoppm = '/usr/bin/pdftoppm';

    /**
     * Pdf to text
     *
     * @param string $path Pdf path
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function pdf2text(string $path, string $optimizer = '') : string
    {
        $text   = '';
        $tmpDir = \sys_get_temp_dir();

        $out = \tempnam($tmpDir, 'oms_pdf_');
        if ($out === false) {
            return '';
        }

        if (\is_file(self::$pdftotext)) {
            SystemUtils::runProc(
                self::$pdftotext, '-layout '
                    . \escapeshellarg($path) . ' '
                    . \escapeshellarg($out)
            );
        }

        $text = \file_get_contents($out);
        \unlink($out);

        if ($text === false) {
            $text = '';
        }

        if (\strlen($text) < 256) {
            $out = \tempnam($tmpDir, 'oms_pdf_');
            if ($out === false) {
                return '';
            }

            if (\is_file(self::$pdftoppm)) {
                SystemUtils::runProc(
                    self::$pdftoppm,
                    '-jpeg -r 300 '
                        . \escapeshellarg($path) . ' '
                        . \escapeshellarg($out)
                );
            }

            $files = \glob($out . '*');
            if ($files === false) {
                \unlink($out);

                return $text === false ? '' : $text;
            }

            foreach ($files as $file) {
                if (!StringUtils::endsWith($file, '.jpg')
                    && !StringUtils::endsWith($file, '.png')
                    && !StringUtils::endsWith($file, '.gif')
                ) {
                    continue;
                }

                /* Too slow
                Thresholding::integralThresholding($file, $file);
                Skew::autoRotate($file, $file, 10);
                */

                if (!empty($optimizer) && \is_file($optimizer)) {
                    SystemUtils::runProc(
                        $optimizer,
                        \escapeshellarg($file) . ' '
                        . \escapeshellarg($file)
                    );
                }

                $ocr  = new TesseractOcr();
                $text = $ocr->parseImage($file);

                \unlink($file);
            }

            \unlink($out);
        }

        return $text;
    }
}
