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

namespace phpOMS\tests\Application;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Application\InstallerAbstract;

/**
 * @testdox phpOMS\tests\Application\InstallerAbstractTest: Application installer
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
        $this->installer   = new class() extends InstallerAbstract {
        	public const PATH = __DIR__ . '/Invalid';
        };
    }

    /**
     * @covers phpOMS\Application\InstallerAbstract
     * @group framework
     */
    public function testInvalidTheme() : void
    {
    	$this->installer::installTheme(__DIR__, 'Invalid');
    	self::assertFalse(\is_dir(__DIR__ . '/css'));
    }
}