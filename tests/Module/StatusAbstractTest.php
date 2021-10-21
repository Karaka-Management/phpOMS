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

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Module\ModuleInfo;
use phpOMS\Module\StatusAbstract;

/**
 * @testdox phpOMS\tests\Module\StatusAbstractTest: Abstract module
 *
 * @internal
 */
final class StatusAbstractTest extends \PHPUnit\Framework\TestCase
{
	protected StatusAbstract $status;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
    	$this->status = new class() extends StatusAbstract
    	{
            public const PATH = __DIR__ . '/../../../Modules/Invalid';
    	};
    }

    /**
     * @covers phpOMS\Module\StatusAbstract
     * @group framework
     */
    public function testInvalidModulePathActivation() : void
    {
        $moduleInfo = new ModuleInfo(__DIR__ . '/info.json');

        $this->status::activateRoutes($moduleInfo);
        $this->status::activateHooks($moduleInfo);

        self::assertFalse(\is_dir(__DIR__ . '/../../../Modules/Invalid'));
    }
}
