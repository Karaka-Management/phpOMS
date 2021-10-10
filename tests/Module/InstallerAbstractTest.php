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

use phpOMS\Module\InstallerAbstract;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\Module\ModuleInfo;
use Model\CoreSettings;

/**
 * @testdox phpOMS\tests\Module\InstallerAbstractTest: Abstract module
 *
 * @internal
 */
class InstallerAbstractTest extends \PHPUnit\Framework\TestCase
{
	protected InstallerAbstract $installer;

	/**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
    	$this->installer = new class() extends InstallerAbstract
    	{

    	};
    }

    /**
     * @covers phpOMS\Module\InstallerAbstract
     * @group framework
     */
    public function testInvalidModuleInstall() : void
    {
    	$this->expectException(\UnexpectedValueException::class);

    	$this->installer::install(
    		new DatabasePool(),
    		new ModuleInfo(__DIR__),
    		new CoreSettings()
    	);
    }
}
