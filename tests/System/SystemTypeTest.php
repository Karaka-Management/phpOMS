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
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\System;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\System\SystemType;

class SystemTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(4, \count(SystemType::getConstants()));
        self::assertEquals(1, SystemType::UNKNOWN);
        self::assertEquals(2, SystemType::WIN);
        self::assertEquals(3, SystemType::LINUX);
        self::assertEquals(4, SystemType::OSX);
    }
}
