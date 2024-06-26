<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests;

use phpOMS\Preloader;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Preloader::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\PreloaderTest: Class preloader')]
final class PreloaderTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPreloading() : void
    {
        $includes = \get_included_files();
        self::assertFalse(\in_array(\realpath(__DIR__ . '/PreloadTest/Preload1.php'), $includes));
        self::assertFalse(\in_array(\realpath(__DIR__ . '/PreloadTest/Sub/Preload2.php'), $includes));
        self::assertFalse(\in_array(\realpath(__DIR__ . '/PreloadTest/Sub/Preload3.php'), $includes));

        $preloader = new Preloader();
        $preloader->ignore(__DIR__ . '/PreloadTest/Sub/Preload3.php')
            ->includePath(__DIR__ . '/PreloadTest')
            ->includePath(__DIR__ . '/Preload0.php')
            ->includePath(__DIR__ . '/PreloadTest/Sub/Preload3.php')
            ->load();

        $includes = \get_included_files();
        self::assertTrue(\in_array(\realpath(__DIR__ . '/PreloadTest/Preload1.php'), $includes));
        self::assertTrue(\in_array(\realpath(__DIR__ . '/PreloadTest/Sub/Preload2.php'), $includes));
        self::assertFalse(\in_array(\realpath(__DIR__ . '/PreloadTest/Sub/Preload3.php'), $includes));
    }
}
