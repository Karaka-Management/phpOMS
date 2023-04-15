<?php
/**
 * Karaka
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

use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\Module\ModuleInfo;
use phpOMS\Module\UninstallerAbstract;

/**
 * @testdox phpOMS\tests\Module\UninstallerAbstractTest: Abstract module uninstaller
 *
 * @internal
 */
final class UninstallerAbstractTest extends \PHPUnit\Framework\TestCase
{
	protected UninstallerAbstract $uninstaller;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
    	$this->uninstaller = new class() extends UninstallerAbstract
    	{
            public const PATH = __DIR__ . '/invalid';
    	};
    }

    /**
     * @testdox A missing database schema will not perform any database operations
     * @covers phpOMS\Module\UninstallerAbstract
     * @group framework
     */
    public function testMissingDbFileUninstall() : void
    {
        $this->uninstaller::dropTables(
            new DatabasePool(),
            new ModuleInfo(__DIR__)
        );

        self::assertFalse(\file_exists($this->uninstaller::PATH . '/Install/db.json'));
    }
}
