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

use phpOMS\System\OperatingSystem;
use phpOMS\System\SystemType;

/**
 * @testdox phpOMS\tests\System\OperatingSystemTest: Operating system
 * @internal
 */
final class OperatingSystemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The current operating system can be returned
     * @covers \phpOMS\System\OperatingSystem
     * @group framework
     */
    public function testSystem() : void
    {
        self::assertNotEquals(SystemType::UNKNOWN, OperatingSystem::getSystem());
    }
}
