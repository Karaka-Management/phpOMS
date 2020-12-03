<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Ai\Ocr;

use phpOMS\Ai\Ocr\BasicOcr;

/**
 * @internal
 */
class BasicOcrTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Ai\Ocr\BasicOcr
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
            $ocr->match(__DIR__ . '/t10k-images-idx3-ubyte', 3, 5)
        );
    }

    /**
     * @covers phpOMS\Ai\Ocr\BasicOcr
     * @group framework
     */
    public function testInvalidImagePath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);
        $ocr = new BasicOcr();
        $ocr->trainWith(__DIR__ . '/invalid', __DIR__ . '/train-labels-idx1-ubyte', 1);
    }

    /**
     * @covers phpOMS\Ai\Ocr\BasicOcr
     * @group framework
     */
    public function testInvalidLabelPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);
        $ocr = new BasicOcr();
        $ocr->trainWith(__DIR__ . '/train-images-idx3-ubyte', __DIR__ . '/invalid', 1);
    }
}
