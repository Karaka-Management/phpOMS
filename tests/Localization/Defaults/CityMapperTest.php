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

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\City;
use phpOMS\Localization\Defaults\CityMapper;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\DataStorage\Database\Connection\SQLiteConnection;

class CityMapperTest extends \PHPUnit\Framework\TestCase
{
    static function setUpBeforeClass()
    {
        $con = new SqliteConnection([
            'prefix' => '',
            'db'     => 'sqlite',
            'database'   => realpath(__DIR__ . '/../../../Localization/Defaults/localization.sqlite'),
        ]);

        DataMapperAbstract::setConnection($con);
    }

    public function testR()
    {
        $obj = CityMapper::get(101079);
        self::assertEquals('DE', $obj->getCountryCode());
        self::assertEquals('Frankfurt', $obj->getName());
        self::assertEquals(60322, $obj->getPostal());
        self::assertGreaterThan(50, $obj->getLat());
        self::assertGreaterThan(8, $obj->getLong());
        self::assertLessThan(51, $obj->getLat());
        self::assertLessThan(9, $obj->getLong());
    }

    static function tearDownAfterClass()
    {
        DataMapperAbstract::setConnection($GLOBALS['dbpool']->get());
    }
}
