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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\File;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\RnG\File::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\RnG\FileTest: File extension randomizer')]
final class FileTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A random file extension can be generated')]
    public function testRnGExtension() : void
    {
        self::assertMatchesRegularExpression('/^[a-z0-9]{1,5}$/', File::generateExtension());
    }
}
