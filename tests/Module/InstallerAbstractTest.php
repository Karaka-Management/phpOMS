<?php
/**
 * Karaka
 *
 * PHP Version 8.0
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
use phpOMS\DataStorage\Database\DatabasePool;
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
    }

    /**
     * @covers phpOMS\Module\InstallerAbstract
     * @group framework
     */
    public function testInvalidModuleInstall() : void
    {
    	$this->expectException(\UnexpectedValueException::class);

    	$this->installer::install(
    		new class() extends ApplicationAbstract {},
    		new ModuleInfo(__DIR__),
    		new CoreSettings()
    	);
    }
}
