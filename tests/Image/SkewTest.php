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

use phpOMS\Image\Skew;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Image\Skew::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Image\SkewTest: Image skew')]
final class SkewTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\Group('slow')]
    #[\PHPUnit\Framework\Attributes\TestDox('A image can be automatically unskewed')]
    public function testSkew() : void
    {
        Skew::autoRotate(
            __DIR__ . '/tilted.jpg',
            __DIR__ . '/test_binary_untilted.jpg',
            10,
            [150, 75],
            [1700, 900]
        );

        self::assertTrue(\is_file(__DIR__ . '/test_binary_untilted.jpg'));
    }
}
