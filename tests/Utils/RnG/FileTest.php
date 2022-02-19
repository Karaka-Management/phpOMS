<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\File;

/**
 * @testdox phpOMS\tests\Utils\RnG\FileTest: File extension randomizer
 *
 * @internal
 */
final class FileTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A random file extension can be generated
     * @covers phpOMS\Utils\RnG\File
     * @group framework
     */
    public function testRnGExtension() : void
    {
        self::assertMatchesRegularExpression('/^[a-z0-9]{1,5}$/', File::generateExtension());
    }
}
