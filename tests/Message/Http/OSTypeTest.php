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

namespace Tests\PHPUnit\phpOMS\Message\Http;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Message\Http\OSType;

class OSTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(23, count(OSType::getConstants()));
        self::assertEquals(OSType::getConstants(), array_unique(OSType::getConstants()));
    }
}
