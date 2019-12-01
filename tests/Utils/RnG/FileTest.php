<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\File;

/**
 * @testdox phpOMS\tests\Utils\RnG\FileTest: File extension randomizer
 *
 * @internal
 */
class FileTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A random file extension can be generated
     * @covers phpOMS\Utils\RnG\File
     */
    public function testRnGExtension() : void
    {
        self::assertRegExp('/^[a-z0-9]{1,5}$/', File::generateExtension());
    }
}
