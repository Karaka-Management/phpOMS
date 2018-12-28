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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\Ip;

class IpTest extends \PHPUnit\Framework\TestCase
{
    public function testIp() : void
    {
        self::assertTrue(\abs(1527532998.0 - Ip::ip2Float('91.12.77.198')) < 1);
    }
}
