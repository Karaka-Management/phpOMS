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

use phpOMS\Application\StatusAbstract;

/**
 * @testdox phpOMS\tests\Application\StatusAbstractTest: Application status
 *
 * @internal
 */
class StatusAbstractTest extends \PHPUnit\Framework\TestCase
{
	protected StatusAbstract $status;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->status      = new class() extends StatusAbstract {
        	public const PATH = __DIR__ . '/Invalid';
        };
    }

    /**
     * @covers phpOMS\Application\StatusAbstract
     * @group framework
     */
    public function testInvalidAppPathActivation() : void
    {
    	$this->status::activateRoutes();
        $this->status::activateHooks();

        self::assertFalse(\is_file(__DIR__ . '/Routes.php'));
    }
}
