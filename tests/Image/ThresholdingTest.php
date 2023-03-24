<?php
/**
 * Karaka
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
 * @internal
 */
final class ThresholdingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testThresholding() : void
    {
        Thresholding::integralThresholding(__DIR__ . '/img1.png', __DIR__ . '/test_img1_integral_thresholding.png');
        Thresholding::integralThresholding(__DIR__ . '/img2.jpg', __DIR__ . '/test_img2_integral_thresholding.jpg');
    }
}
