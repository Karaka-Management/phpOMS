<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\System;

use phpOMS\System\SystemUtils;

require_once __DIR__ . '/../Autoloader.php';

class SystemUtilsTest extends \PHPUnit\Framework\TestCase
{
    public function testSystem()
    {
        self::assertGreaterThan(0, SystemUtils::getRAM());
        self::assertGreaterThan(0, SystemUtils::getCpuUsage());

        if (stristr(PHP_OS, 'WIN')) {
            self::assertEquals(0, SystemUtils::getRAMUsage());
        }

        if (!stristr(PHP_OS, 'WIN')) {
            self::assertGreaterThan(0, SystemUtils::getRAMUsage());
        }
    }
}
