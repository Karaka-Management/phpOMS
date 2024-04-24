<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Localization\Defaults\CountryMapper::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\Defaults\CountryMapperTest: Country database mapper')]
final class CountryMapperTest extends \PHPUnit\Framework\TestCase
{
    private static SQLiteConnection $con;

    public static function setUpBeforeClass() : void
    {
        self::$con = new SqliteConnection([
            'db'       => 'sqlite',
            'database' => \realpath(__DIR__ . '/../../../Localization/Defaults/localization.sqlite'),
        ]);

        self::$con->connect();

        DataMapperFactory::db(self::$con);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The model can be read from the database')]
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
        self::$con->close();
        DataMapperFactory::db($GLOBALS['dbpool']->get());
    }
}
