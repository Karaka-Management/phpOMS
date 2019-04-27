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
 declare(strict_types=1);

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Module\ModuleAbstract;

/**
 * @internal
 */
class ModuleAbstractTest extends \PHPUnit\Framework\TestCase
{
    public function testModuleAbstract() : void
    {
        $moduleClass = new class(null) extends ModuleAbstract {
            const MODULE_VERSION           = '1.2.3';
            const MODULE_NAME              = 'Test';
            const MODULE_ID                = 2;
            protected static $dependencies = [1, 2];
        };

        self::assertEquals([1, 2], $moduleClass->getDependencies());
        self::assertEquals(2, $moduleClass::MODULE_ID);
        self::assertEquals('1.2.3', $moduleClass::MODULE_VERSION);
        self::assertEquals([], $moduleClass::getLocalization('invalid', 'invalid'));
    }
}
