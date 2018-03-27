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

class ModuleFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testFactory()
    {
        self::assertInstanceOf(\Modules\Admin\Controller::class, ModuleFactory::getInstance('Admin', new class extends ApplicationAbstract {}));
    }
}
