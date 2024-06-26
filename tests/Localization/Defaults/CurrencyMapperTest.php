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
use phpOMS\Localization\Defaults\Currency;
use phpOMS\Localization\Defaults\CurrencyMapper;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Localization\Defaults\CurrencyMapper::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\Defaults\CurrencyMapperTest: Currency database mapper')]
final class CurrencyMapperTest extends \PHPUnit\Framework\TestCase
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
        /** @var Currency $obj */
        $obj = CurrencyMapper::get()->where('id', 50)->execute();
        self::assertEquals('Euro', $obj->getName());
        self::assertEquals('EUR', $obj->getCode());
        self::assertEquals('978', $obj->getNumber());
        self::assertEquals('€', $obj->getSymbol());
        self::assertEquals(100, $obj->getSubunits());
        self::assertEquals('2', $obj->getDecimals());
        self::assertStringContainsString('Germany', $obj->getCountries());
    }

    public static function tearDownAfterClass() : void
    {
        self::$con->close();
        DataMapperFactory::db($GLOBALS['dbpool']->get());
    }
}
