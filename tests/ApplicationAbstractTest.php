<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests;

use phpOMS\ApplicationAbstract;
use phpOMS\UnhandledHandler;

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
