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
use phpOMS\Localization\Defaults\Country;
use phpOMS\Localization\Defaults\CountryMapper;

/**
 * @testdox phpOMS\tests\Localization\Defaults\CountryMapperTest: Country database mapper
 *
 * @internal
 */
final class CountryMapperTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass() : void
    {
        $con = new SqliteConnection([
            'db'         => 'sqlite',
            'database'   => \realpath(__DIR__ . '/../../../Localization/Defaults/localization.sqlite'),
        ]);

        DataMapperFactory::db($con);
    }

    /**
     * @testdox The model can be read from the database
     * @covers phpOMS\Localization\Defaults\CountryMapper
     * @group framework
     */
    public function testR() : void
    {
        /** @var Country $obj */
        $obj = CountryMapper::get()->where('id', 83)->execute();
        self::assertEquals('Germany', $obj->getName());
        self::assertEquals('DE', $obj->getCode2());
        self::assertEquals('DEU', $obj->getCode3());
        self::assertEquals(276, $obj->getNumeric());
    }

    public static function tearDownAfterClass() : void
    {
        DataMapperFactory::db($GLOBALS['dbpool']->get());
    }
}
