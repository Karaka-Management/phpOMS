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

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\Localization\Defaults\CurrencyMapper;

/**
 * @testdox phpOMS\tests\Localization\Defaults\CurrencyMapperTest: Currency database mapper
 *
 * @internal
 */
class CurrencyMapperTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass() : void
    {
        $con = new SqliteConnection([
            'prefix' => '',
            'db'     => 'sqlite',
            'database'   => \realpath(__DIR__ . '/../../../Localization/Defaults/localization.sqlite'),
        ]);

        DataMapperAbstract::setConnection($con);
    }

    /**
     * @testdox The model can be read from the database
     * @group framework
     */
    public function testR() : void
    {
        $obj = CurrencyMapper::get(50);
        self::assertEquals('Euro', $obj->getName());
        self::assertEquals('EUR', $obj->getCode());
        self::assertEquals(978, $obj->getNumber());
        self::assertEquals(2, $obj->getDecimals());
        self::assertStringContainsString('Germany', $obj->getCountries());
    }

    public static function tearDownAfterClass() : void
    {
        DataMapperAbstract::setConnection($GLOBALS['dbpool']->get());
    }
}
