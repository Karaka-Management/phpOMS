<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests;

use phpOMS\ApplicationAbstract;

class ApplicationAbstractTest extends \PHPUnit\Framework\TestCase
{
    public function testGetSet()
    {
        $obj = new class extends ApplicationAbstract {};

        $obj->appName = 'Test';
        self::assertEquals('Test', $obj->appName);

        $obj->appName = 'ABC';
        self::assertEquals('Test', $obj->appName);
    }
}
