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

namespace phpOMS\tests\Application;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Application\ApplicationInfo;
use phpOMS\Application\UninstallerAbstract;
use phpOMS\DataStorage\Database\DatabasePool;

/**
 * @testdox phpOMS\tests\Application\UninstallerAbstractTest: Abstract module
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
     * @covers phpOMS\Application\UninstallerAbstract
     * @group framework
     */
    public function testMissingDbFileUninstall() : void
    {
        $this->uninstaller::dropTables(
            new DatabasePool(),
            new ApplicationInfo(__DIR__)
        );

        self::assertFalse(\file_exists($this->uninstaller::PATH . '/Install/db.json'));
    }
}
