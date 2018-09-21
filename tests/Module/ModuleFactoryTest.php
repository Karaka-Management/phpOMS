<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\ApplicationAbstract;
use phpOMS\Module\ModuleFactory;
use phpOMS\Module\NullModule;

class ModuleFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testFactory()
    {
        $instance = NullModule::class;
        if (\file_exists(__DIR__ . '/../../../Modules')) {
            $instance = \Modules\Admin\Controller\ApiController::class;
        }

        self::assertInstanceOf($instance, ModuleFactory::getInstance('Admin', new class extends ApplicationAbstract { protected $appName = 'Api'; }));
    }
}
