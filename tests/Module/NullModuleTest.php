<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\ApplicationAbstract;
use phpOMS\Module\NullModule;

/**
 * @internal
 */
class NullModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testModule() : void
    {
        $app = new class() extends ApplicationAbstract
        {
        };

        self::assertInstanceOf('\phpOMS\Module\ModuleAbstract', new NullModule($app));
    }
}
