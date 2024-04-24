<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
final class PdfParser
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
    public static function parsePdf(string $path, string $output = 'html') : string
    {
        return self::pdf2text($path);
    }

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

        // Try to read pdf directly
        // Important: not all PDFs are searchable, some behave like an image
        if (\is_file(self::$pdftotext)) {
            try {
                SystemUtils::runProc(
                    self::$pdftotext, '-layout '
                        . \escapeshellarg($path) . ' '
                        . \escapeshellarg($out)
                );
            } catch (\Throwable $_) {
                \unlink($out);

                return '';
            }
        }

        $text = \file_get_contents($out);
        \unlink($out);

        if ($text === false) {
            $text = '';
        }

        if (\strlen($text) > 255) {
            return $text;
        }

        // Couldn't read text from pdf -> transform to image and run OCR on image
        $out = \tempnam($tmpDir, 'oms_pdf_');
        if ($out === false) {
            return '';
        }

        if (\is_file(self::$pdftoppm)) {
            try {
                SystemUtils::runProc(
                    self::$pdftoppm,
                    '-jpeg -r 300 '
                        . \escapeshellarg($path) . ' '
                        . \escapeshellarg($out)
                );
            } catch (\Throwable $_) {
                \unlink($out);

                return '';
            }
        }

        $files = \glob($out . '*');
        if ($files === false) {
            \unlink($out);

            return $text === false ? '' : $text;
        }

        foreach ($files as $file) {
            if (!StringUtils::endsWith($file, '.jpg')
                && !StringUtils::endsWith($file, '.jpeg')
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
                try {
                    SystemUtils::runProc(
                        $optimizer,
                        \escapeshellarg($file) . ' '
                        . \escapeshellarg($file)
                    );
                } catch (\Throwable $_) {
                    \unlink($file);

                    continue;
                }
            }

            $ocr  = new TesseractOcr();
            $text = $ocr->parseImage($file);

            \unlink($file);
        }

        \unlink($out);

        return $text;
    }
}
