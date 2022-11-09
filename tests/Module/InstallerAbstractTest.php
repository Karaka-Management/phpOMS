<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use Model\CoreSettings;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;

/**
 * @testdox phpOMS\tests\Module\InstallerAbstractTest: Abstract module
 *
 * @internal
 */
final class InstallerAbstractTest extends \PHPUnit\Framework\TestCase
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

        $this->installer->dbPool = $GLOBALS['dbpool'];
    }

    /**
     * @covers phpOMS\Module\InstallerAbstract
     * @group framework
     */
    public function testInvalidModuleInstall() : void
    {
        $this->expectException(\Error::class);

        $app = new class() extends ApplicationAbstract {};
        $app->dbPool = $GLOBALS['dbpool'];

    	$this->installer::install(
    		$app,
    		new ModuleInfo(__DIR__),
    		new CoreSettings()
    	);
    }
}
