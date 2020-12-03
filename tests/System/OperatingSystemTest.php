<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\System;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\System\OperatingSystem;
use phpOMS\System\SystemType;

/**
 * @internal
 */
class OperatingSystemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\System\OperatingSystem
     * @group framework
     */
    public function testSystem() : void
    {
        self::assertNotEquals(SystemType::UNKNOWN, OperatingSystem::getSystem());
    }
}
