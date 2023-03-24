<?php
/**
 * Karaka
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

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;
use phpOMS\Localization\Defaults\City;
use phpOMS\Localization\Defaults\CityMapper;

/**
 * @testdox phpOMS\tests\Localization\Defaults\CityMapperTest: City database mapper
 *
 * @internal
 */
final class CityMapperTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass() : void
    {
        $con = new SqliteConnection([
            'prefix'     => '',
            'db'         => 'sqlite',
            'database'   => \realpath(__DIR__ . '/../../../Localization/Defaults/localization.sqlite'),
        ]);

        DataMapperFactory::db($con);
    }

    /**
     * @testdox The model can be read from the database
     * @covers phpOMS\Localization\Defaults\CityMapper
     * @group framework
     */
    public function testR() : void
    {
        /** @var City $obj */
        $obj = CityMapper::get()->where('id', 101079)->execute();
        self::assertEquals('DE', $obj->getCountryCode());
        self::assertEquals('Frankfurt', $obj->getName());
        self::assertEquals(60322, $obj->getPostal());
        self::assertGreaterThan(50, $obj->getLat());
        self::assertGreaterThan(8, $obj->getLong());
        self::assertLessThan(51, $obj->getLat());
        self::assertLessThan(9, $obj->getLong());
    }

    public static function tearDownAfterClass() : void
    {
        DataMapperFactory::db($GLOBALS['dbpool']->get());
    }
}
