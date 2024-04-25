<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Ai\Ocr\Tesseract
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Ai\Ocr\Tesseract;

use phpOMS\System\File\PathException;
use phpOMS\System\SystemUtils;

/**
 * Tesseract api
 *
 * @package phpOMS\Ai\Ocr\Tesseract
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class TesseractOcr
{
    /**
     * Tesseract path.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $bin = '/usr/bin/tesseract';

    /**
     * Set tesseract binary.
     *
     * @param string $path tesseract path
     *
     * @return void
     *
     * @throws PathException This exception is thrown if the binary path doesn't exist
     *
     * @since 1.0.0
     */
    public static function setBin(string $path) : void
    {
        if (\realpath($path) === false) {
            throw new PathException($path);
        }

        self::$bin = \realpath($path);
    }

    /**
     * Prase image
     *
     * @param string $image     Image path
     * @param array  $languages Languages to use
     * @param int    $psm       Page segmentation mode (0 - 13)
     *                          0    Orientation and script detection (OSD) only.
     *                          1    Automatic page segmentation with OSD.
     *                          2    Automatic page segmentation, but no OSD, or OCR.
     *                          3    Fully automatic page segmentation, but no OSD. (Default)
     *                          4    Assume a single column of text of variable sizes.
     *                          5    Assume a single uniform block of vertically aligned text.
     *                          6    Assume a single uniform block of text.
     *                          7    Treat the image as a single text line.
     *                          8    Treat the image as a single word.
     *                          9    Treat the image as a single word in a circle.
     *                          10   Treat the image as a single character.
     *                          11   Sparse text. Find as much text as possible in no particular order.
     *                          12   Sparse text with OSD.
     *                          13   Raw line. Treat the image as a single text line, bypassing hacks that are Tesseract-specific.
     * @param int    $oem       OCR engine modes
     *                          0    Legacy engine only.
     *                          1    Neural nets LSTM engine only.
     *                          2    Legacy + LSTM engines.
     *                          3    Default, based on what is available
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function parseImage(string $image, array $languages = ['eng', 'deu'], int $psm = 3, int $oem = 3) : string
    {
        $temp = \tempnam(\sys_get_temp_dir(), 'oms_ocr_');
        if ($temp === false) {
            return '';
        }

        $extension = 'png';
        try {
            // Tesseract needs higher dpi to work properly (identify + adjust if necessary)
            $dpi = (int) \trim(\implode('', SystemUtils::runProc(
                'identify',
                '-quiet -format "%x" ' . $image
            )));

            if ($dpi < 300) {
                $split     = \explode('.', $image);
                $extension = \end($split);

                SystemUtils::runProc(
                    'convert',
                    '-units PixelsPerInch ' . $image . ' -resample 300 ' . $temp . '.' . $extension
                );

                $image = $temp . '.' . $extension;
            }

            // Do actual parsing
            SystemUtils::runProc(
                self::$bin,
                $image . ' '
                . $temp
                . ' -c preserve_interword_spaces=1'
                . ' --psm ' . $psm
                . ' --oem ' . $oem
                . (empty($languages) ? '' : ' -l ' . \implode('+', $languages))
            );
        } catch (\Throwable $_) {
            if (\is_file($temp . '.' . $extension)) {
                \unlink($temp . '.' . $extension);
            }

            return '';
        }

        if (\is_file($temp . '.' . $extension)) {
            \unlink($temp . '.' . $extension);
        }

        $filepath = \is_file($temp . '.txt')
            ? $temp . '.txt'
            : $temp;

        if (!\is_file($filepath)) {
            // @codeCoverageIgnoreStart
            \unlink($temp);

            return '';
            // @codeCoverageIgnoreEnd
        }

        $parsed = \file_get_contents($filepath);
        if ($parsed === false) {
            $parsed = '';
        }

        if (\is_file($filepath)) {
            \unlink($filepath);
        }

        if (\is_file($temp)) {
            \unlink($temp);
        }

        return \trim($parsed);
    }
}
