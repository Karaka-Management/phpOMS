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
        Thresholding::integralThresholding(__DIR__ . '/img1.png', __DIR__ . '/test_img1.png');
        Thresholding::integralThresholding(__DIR__ . '/img2.jpg', __DIR__ . '/test_img2.jpg');
    }
}
