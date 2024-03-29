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

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Module\ModuleInfo;
use phpOMS\Module\StatusAbstract;

/**
 * @testdox phpOMS\tests\Module\StatusAbstractTest: Abstract module status
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
     * A invalid module path cannot be activated
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
