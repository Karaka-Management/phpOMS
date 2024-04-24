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

namespace phpOMS\tests\System;

use phpOMS\System\SystemUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\System\SystemUtils::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\System\SystemUtilsTest: System information')]
final class SystemUtilsTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Test if it is possible to get information about the available RAM and usage')]
    public function testRAM() : void
    {
        self::assertTrue(SystemUtils::getRAM() >= 0);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Test if it is possible to get information about the CPU usage')]
    public function testCPUUsage() : void
    {
        self::assertGreaterThan(0, SystemUtils::getCpuUsage());
    }

    public function testHostname() : void
    {
        self::assertGreaterThan(0, \strlen(SystemUtils::getHostname()));
    }
}
