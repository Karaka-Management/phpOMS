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

namespace phpOMS\tests\System;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\System\SystemType;

/**
 * @internal
 */
class SystemTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(4, SystemType::getConstants());
        self::assertEquals(1, SystemType::UNKNOWN);
        self::assertEquals(2, SystemType::WIN);
        self::assertEquals(3, SystemType::LINUX);
        self::assertEquals(4, SystemType::OSX);
    }
}
