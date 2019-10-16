<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\Ip;

/**
 * @internal
 */
class IpTest extends \PHPUnit\Framework\TestCase
{
    public function testIp() : void
    {
        self::assertTrue(\abs(1527532998.0 - Ip::ip2Float('91.12.77.198')) < 1);
    }
}
