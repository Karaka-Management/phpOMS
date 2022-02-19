<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Ai\Ocr\Tesseract;

use phpOMS\Ai\Ocr\Tesseract\TesseractOcr;
use phpOMS\Image\Kernel;
use phpOMS\Image\Skew;
use phpOMS\Image\Thresholding;

/**
 * @internal
 */
final class TesseractOcrTest extends \PHPUnit\Framework\TestCase
{
    private function outputTest(string $name, float $m1, float $m2) : void
    {
        if (false) {
            return;
        }

        echo "\n";
        echo $name . ":\n";
        echo 'Match1: ' . $m1 . " \n";
        echo 'Match2: ' . $m2 . " \n";
    }

    /**
     * @covers phpOMS\Ai\Ocr\Tesseract\TesseractOcr
     * @group framework
     */
    public function testOcrBasic() : void
    {
        $ocr = new TesseractOcr();

        $parsed = $ocr->parseImage(__DIR__ . '/img1.png');
        \similar_text(\file_get_contents(__DIR__ . '/actual.txt'), $parsed, $m1);
        \similar_text($parsed, \file_get_contents(__DIR__ . '/actual.txt'), $m2);

        \file_put_contents(__DIR__ . '/basic.txt', $parsed);
        $this->outputTest('No Preprocessing', $m1, $m2);

        self::assertGreaterThan(0.5, $m1);
        self::assertGreaterThan(0.5, $m2);
    }

    /**
     * @covers phpOMS\Ai\Ocr\Tesseract\TesseractOcr
     * @group framework
     */
    public function testOcrWithThresholding() : void
    {
        $ocr = new TesseractOcr();

        \copy(__DIR__ . '/img1.png', __DIR__ . '/thresholding.png');

        // preprocessing
        Thresholding::integralThresholding(__DIR__ . '/thresholding.png', __DIR__ . '/thresholding.png');
        $parsed = $ocr->parseImage(__DIR__ . '/thresholding.png');
        \similar_text(\file_get_contents(__DIR__ . '/actual.txt'), $parsed, $m1);
        \similar_text($parsed, \file_get_contents(__DIR__ . '/actual.txt'), $m2);

        \file_put_contents(__DIR__ . '/thresholding.txt', $parsed);
        $this->outputTest('Thresholding', $m1, $m2);

        self::assertGreaterThan(0.75, $m1);
        self::assertGreaterThan(0.75, $m2);
    }

    /**
     * @covers phpOMS\Ai\Ocr\Tesseract\TesseractOcr
     * @group framework
     */
    public function testOcrWithThresholdingRotating() : void
    {
        $ocr = new TesseractOcr();

        \copy(__DIR__ . '/img1.png', __DIR__ . '/thresholding_rotating.png');

        // preprocessing
        Thresholding::integralThresholding(__DIR__ . '/thresholding_rotating.png', __DIR__ . '/thresholding_rotating.png');
        Skew::autoRotate(
            __DIR__ . '/thresholding_rotating.png',
            __DIR__ . '/thresholding_rotating.png',
            10,
            [150, 75],
            [1700, 900]
        );

        $parsed = $ocr->parseImage(__DIR__ . '/thresholding_rotating.png');
        \similar_text(\file_get_contents(__DIR__ . '/actual.txt'), $parsed, $m1);
        \similar_text($parsed, \file_get_contents(__DIR__ . '/actual.txt'), $m2);

        \file_put_contents(__DIR__ . '/thresholding_rotating.txt', $parsed);
        $this->outputTest('Thresholding + Rotating', $m1, $m2);

        self::assertGreaterThan(0.9, $m1);
        self::assertGreaterThan(0.9, $m2);
    }

    /**
     * @covers phpOMS\Ai\Ocr\Tesseract\TesseractOcr
     * @group framework
     */
    public function testOcrWithSharpeningThresholdingRotating() : void
    {
        $ocr = new TesseractOcr();

        \copy(__DIR__ . '/img1.png', __DIR__ . '/sharpening_thresholding_rotating.png');

        // preprocessing
        Kernel::convolve(__DIR__ . '/sharpening_thresholding_rotating.png', __DIR__ . '/sharpening_thresholding_rotating.png', Kernel::KERNEL_SHARPEN);
        Thresholding::integralThresholding(__DIR__ . '/sharpening_thresholding_rotating.png', __DIR__ . '/sharpening_thresholding_rotating.png');
        Skew::autoRotate(
            __DIR__ . '/sharpening_thresholding_rotating.png',
            __DIR__ . '/sharpening_thresholding_rotating.png',
            10,
            [150, 75],
            [1700, 900]
        );

        $parsed = $ocr->parseImage(__DIR__ . '/sharpening_thresholding_rotating.png');
        \similar_text(\file_get_contents(__DIR__ . '/actual.txt'), $parsed, $m1);
        \similar_text($parsed, \file_get_contents(__DIR__ . '/actual.txt'), $m2);

        \file_put_contents(__DIR__ . '/sharpening_thresholding_rotating.txt', $parsed);
        $this->outputTest('Sharpening + Thresholding + Rotating', $m1, $m2);

        self::assertGreaterThan(0.9, $m1);
        self::assertGreaterThan(0.9, $m2);
    }
}
