<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Application\UninstallerAbstract::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Application\UninstallerAbstractTest: Abstract application uninstaller')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database schema will not perform any database operations')]
    public function testMissingDbFileUninstall() : void
    {
        $this->uninstaller::dropTables(
            new DatabasePool(),
            new ApplicationInfo(__DIR__)
        );

        self::assertFalse(\file_exists($this->uninstaller::PATH . '/Install/db.json'));
    }
}
