<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\System;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\System\MimeType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\System\MimeType::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\System\MimeTypeTest: MimeType')]
final class MimeTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The mime type enum vales have the correct format')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        $enums = MimeType::getConstants();

        foreach ($enums as $value) {
            if (\stripos($value, '/') === false) {
                self::assertFalse(true);
            }
        }

        self::assertTrue(true);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The mime type enum vales can be retreived by extension')]
    public function testExtensionToMime() : void
    {
        self::assertEquals('application/pdf', MimeType::extensionToMime('pdf'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A unknown extension returns application/octet-stream')]
    public function testInvalidExtensionToMime() : void
    {
        self::assertEquals('application/octet-stream', MimeType::extensionToMime('INVALID'));
    }
}
