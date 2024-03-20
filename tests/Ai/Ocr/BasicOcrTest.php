<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Ai\Ocr;

use phpOMS\Ai\Ocr\BasicOcr;

/**
 * @internal
 */
final class BasicOcrTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \phpOMS\Ai\Ocr\BasicOcr
     * @group framework
     */
    public function testOcr() : void
    {
        $ocr = new BasicOcr();
        $ocr->trainWith(__DIR__ . '/train-images-idx3-ubyte', __DIR__ . '/train-labels-idx1-ubyte', 1000);

        self::assertEquals(
            [
                ['label' => 7, 'prob' => 1], ['label' => 7, 'prob' => 1], ['label' => 7, 'prob' => 1],
                ['label' => 2, 'prob' => 2 / 3], ['label' => 2, 'prob' => 2 / 3], ['label' => 8, 'prob' => 1 / 3],
                ['label' => 1, 'prob' => 1], ['label' => 1, 'prob' => 1], ['label' => 1, 'prob' => 1],
                ['label' => 0, 'prob' => 1], ['label' => 0, 'prob' => 1], ['label' => 0, 'prob' => 1],
                ['label' => 4, 'prob' => 2 / 3], ['label' => 9, 'prob' => 1 / 3], ['label' => 4, 'prob' => 2 / 3],
            ],
            $ocr->matchImage(__DIR__ . '/t10k-images-idx3-ubyte', 3, 5)
        );
    }

    public function testCustomMnistFiles() : void
    {
        $ocr = new BasicOcr();
        $ocr->trainWith(__DIR__ . '/train-images-idx3-ubyte', __DIR__ . '/train-labels-idx1-ubyte', 1000);

        if (\is_file(__DIR__ . '/test-image-ubyte')) {
            \unlink(__DIR__ . '/test-image-ubyte');
            \unlink(__DIR__ . '/test-label-ubyte');
        }

        BasicOcr::imagesToMNIST([__DIR__ . '/3.jpg'], __DIR__ . '/test-image-ubyte', 28);
        BasicOcr::labelsToMNIST(['3'], __DIR__ . '/test-label-ubyte');

        self::assertEquals(3, $ocr->matchImage(__DIR__ . '/test-image-ubyte', 3, 5)[0]['label']);

        if (\is_file(__DIR__ . '/test-image-ubyte')) {
            \unlink(__DIR__ . '/test-image-ubyte');
            \unlink(__DIR__ . '/test-label-ubyte');
        }
    }

    /**
     * @covers \phpOMS\Ai\Ocr\BasicOcr
     * @group framework
     */
    public function testInvalidImagePath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);
        $ocr = new BasicOcr();
        $ocr->trainWith(__DIR__ . '/invalid', __DIR__ . '/train-labels-idx1-ubyte', 1);
    }

    /**
     * @covers \phpOMS\Ai\Ocr\BasicOcr
     * @group framework
     */
    public function testInvalidLabelPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);
        $ocr = new BasicOcr();
        $ocr->trainWith(__DIR__ . '/train-images-idx3-ubyte', __DIR__ . '/invalid', 1);
    }
}
