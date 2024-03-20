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

namespace phpOMS\tests\Image;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Image\Kernel;

/**
 * @testdox phpOMS\tests\Image\KernelTest: Image kernel
 * @internal
 */
final class KernelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The kernel can be applied to an image which is then stored in a new file
     * @group framework
     * @group slow
     * @covers \phpOMS\Image\Kernel
     */
    public function testKernel() : void
    {
        Kernel::convolve(__DIR__ . '/img1.png', __DIR__ . '/test_img1_sharpen.png', Kernel::KERNEL_SHARPEN);
        Kernel::convolve(__DIR__ . '/img1.png', __DIR__ . '/test_img1_blur.png', Kernel::KERNEL_GAUSSUAN_BLUR_3);
        Kernel::convolve(__DIR__ . '/img1.png', __DIR__ . '/test_img1_emboss.png', Kernel::KERNEL_EMBOSS);
        Kernel::convolve(__DIR__ . '/img1.png', __DIR__ . '/test_img1_unsharpen.png', Kernel::KERNEL_UNSHARP_MASKING);

        self::assertTrue(\is_file(__DIR__ . '/test_img1_sharpen.png'));
        self::assertTrue(\is_file(__DIR__ . '/test_img1_blur.png'));
        self::assertTrue(\is_file(__DIR__ . '/test_img1_emboss.png'));
        self::assertTrue(\is_file(__DIR__ . '/test_img1_unsharpen.png'));
    }
}
