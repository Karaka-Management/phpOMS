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

namespace phpOMS\tests\DataStorage\Cache;


use phpOMS\DataStorage\Cache\CacheStatus;

class CacheStatusTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(3, count(CacheStatus::getConstants()));
        self::assertEquals(0, CacheStatus::ACTIVE);
        self::assertEquals(1, CacheStatus::INACTIVE);
        self::assertEquals(2, CacheStatus::ERROR);
    }
}
