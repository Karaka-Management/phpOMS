<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\System;

use phpOMS\System\SystemUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\System\SystemUtils: System information
 *
 * @internal
 */
class SystemUtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Test if it is possible to get information about the available RAM and usage (on Windows)
     */
    public function testRAM() : void
    {
        self::assertGreaterThan(0, SystemUtils::getRAM());

        if (\stristr(\PHP_OS, 'WIN')) {
            self::assertEquals(0, SystemUtils::getRAMUsage());
        }

        if (!\stristr(\PHP_OS, 'WIN')) {
            self::assertGreaterThan(0, SystemUtils::getRAMUsage());
        }
    }

    /**
     * @testdox Test if it is possible to get information about the CPU usage
     */
    public function testCPUUsage() : void
    {
        self::assertGreaterThan(0, SystemUtils::getCpuUsage());
    }
}
