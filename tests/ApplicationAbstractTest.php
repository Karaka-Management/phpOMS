<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests;

use phpOMS\ApplicationAbstract;

/**
 * @internal
 */
class ApplicationAbstractTest extends \PHPUnit\Framework\TestCase
{
    public function testGetSet() : void
    {
        $obj = new class() extends ApplicationAbstract {};

        $obj->appName = 'Test';
        self::assertEquals('Test', $obj->appName);

        $obj->appName = 'ABC';
        self::assertEquals('Test', $obj->appName);
    }
}
