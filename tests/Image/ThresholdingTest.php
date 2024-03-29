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

use phpOMS\Image\Thresholding;

/**
 * @testdox phpOMS\tests\Image\ThresholdingTest: Image thresholding
 * @internal
 */
final class ThresholdingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The thresholding is correctly applied to the image
     * @group framework
     * @covers phpOMS\Image\Thresholding
     */
    public function testThresholding() : void
    {
        Thresholding::integralThresholding(__DIR__ . '/img1.png', __DIR__ . '/test_img1_integral_thresholding.png');
        Thresholding::integralThresholding(__DIR__ . '/img2.jpg', __DIR__ . '/test_img2_integral_thresholding.jpg');

        self::assertTrue(\is_file(__DIR__ . '/test_img1_integral_thresholding.png'));
        self::assertTrue(\is_file(__DIR__ . '/test_img2_integral_thresholding.jpg'));
    }
}
