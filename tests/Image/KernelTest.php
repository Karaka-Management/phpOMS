<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Image;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Image\Kernel;

/**
 * @internal
 */
final class KernelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testKernel() : void
    {
        Kernel::convolve(__DIR__ . '/img1.png', __DIR__ . '/test_img1_sharpen.png', Kernel::KERNEL_SHARPEN);
        Kernel::convolve(__DIR__ . '/img1.png', __DIR__ . '/test_img1_blur.png', Kernel::KERNEL_GAUSSUAN_BLUR_3);
        Kernel::convolve(__DIR__ . '/img1.png', __DIR__ . '/test_img1_emboss.png', Kernel::KERNEL_EMBOSS);
    }
}
