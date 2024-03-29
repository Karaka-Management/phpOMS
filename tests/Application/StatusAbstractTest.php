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

namespace phpOMS\tests\Application;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Application\StatusAbstract;

/**
 * @testdox phpOMS\tests\Application\StatusAbstractTest: Abstract application status
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
        	public const PATH = __DIR__ . '/Invalid';
        };
    }

    /**
     * @testdox A invalid application path cannot be activated
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
