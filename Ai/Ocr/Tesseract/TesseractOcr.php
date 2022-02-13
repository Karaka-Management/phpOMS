<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Ai\Ocr\Tesseract
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Ai\Ocr\Tesseract;

use phpOMS\System\File\PathException;

/**
 * Tesseract api
 *
 * @package phpOMS\Ai\Ocr\Tesseract
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
     * Run git command.
     *
     * @param string $cmd Command to run
     *
     * @return string[]
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    private function run(string $cmd) : array
    {
        if (\strtolower((string) \substr(\PHP_OS, 0, 3)) == 'win') {
            $cmd = 'cd ' . \escapeshellarg(\dirname(self::$bin))
                . ' && ' . \basename(self::$bin)
                . ' -C ' . \escapeshellarg($this->path) . ' '
                . $cmd;
        } else {
            $cmd = \escapeshellarg(self::$bin)
                . ' -C ' . \escapeshellarg($this->path) . ' '
                . $cmd;
        }

        $pipes = [];
        $desc  = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $resource = \proc_open($cmd, $desc, $pipes, $this->path, null);

        if ($resource === false) {
            throw new \Exception();
        }

        $stdout = \stream_get_contents($pipes[1]);
        $stderr = \stream_get_contents($pipes[2]);

        foreach ($pipes as $pipe) {
            \fclose($pipe);
        }

        $status = \proc_close($resource);

        if ($status == -1) {
            throw new \Exception((string) $stderr);
        }

        return $this->parseLines(\trim($stdout === false ? '' : $stdout));
    }

    /**
     * Parse lines.
     *
     * @param string $lines Result of git command
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    private function parseLines(string $lines) : array
    {
        $lineArray = \preg_split('/\r\n|\n|\r/', $lines);
        $lines     = [];

        if ($lineArray === false) {
            return $lines;
        }

        foreach ($lineArray as $line) {
            $temp = \preg_replace('/\s+/', ' ', \trim($line, ' '));

            if (!empty($temp)) {
                $lines[] = $temp;
            }
        }

        return $lines;
    }

    /**
     * Prase image
     *
     * @param string $image     Image path
     * @param array  $languages Languages to use
     * @param int    $psm       Page segmentation mode (0 - 13)
     *                              0    Orientation and script detection (OSD) only.
     *                              1    Automatic page segmentation with OSD.
     *                              2    Automatic page segmentation, but no OSD, or OCR.
     *                              3    Fully automatic page segmentation, but no OSD. (Default)
     *                              4    Assume a single column of text of variable sizes.
     *                              5    Assume a single uniform block of vertically aligned text.
     *                              6    Assume a single uniform block of text.
     *                              7    Treat the image as a single text line.
     *                              8    Treat the image as a single word.
     *                              9    Treat the image as a single word in a circle.
     *                              10   Treat the image as a single character.
     *                              11   Sparse text. Find as much text as possible in no particular order.
     *                              12   Sparse text with OSD.
     *                              13   Raw line. Treat the image as a single text line, bypassing hacks that are Tesseract-specific.
     * @param int    $oem       OCR engine modes
     *                              0    Legacy engine only.
     *                              1    Neural nets LSTM engine only.
     *                              2    Legacy + LSTM engines.
     *                              3    Default, based on what is available
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function parseImage(string $image, array $languages = ['eng'], int $psm = 3, int $oem = 3) : string
    {
        $this->run(
            $image . ' '
            . ($temp = \tempnam(\sys_get_temp_dir(), 'ocr_'))
            . '--psm ' . $psm . ' '
            . '--oem ' . $oem . ' '
            . '-l ' . \implode('+', $languages)
        );

        $parsed = \file_get_contents($temp);

        \unlink($temp);

        return $parsed;
    }
}
