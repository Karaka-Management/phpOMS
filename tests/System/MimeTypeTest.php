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

namespace phpOMS\tests\System;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\System\MimeType;

/**
 * @testdox phpOMS\tests\System\MimeTypeTest: MimeType
 * @internal
 */
final class MimeTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The mime type enum vales have the correct format
     * @group framework
     * @coversNothing
     */
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

    /**
     * @testdox The mime type enum vales can be retreived by extension
     * @covers phpOMS\System\MimeType
     * @group framework
     */
    public function testExtensionToMime() : void
    {
        self::assertEquals('application/pdf', MimeType::extensionToMime('pdf'));
    }

    /**
     * @testdox A unknown extension returns application/octet-stream
     * @covers phpOMS\System\MimeType
     * @group framework
     */
    public function testInvalidExtensionToMime() : void
    {
        self::assertEquals('application/octet-stream', MimeType::extensionToMime('INVALID'));
    }
}
