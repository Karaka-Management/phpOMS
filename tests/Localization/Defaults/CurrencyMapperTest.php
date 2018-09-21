<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Currency;
use phpOMS\Localization\Defaults\CurrencyMapper;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\DataStorage\Database\Connection\SQLiteConnection;

class CurrencyMapperTest extends \PHPUnit\Framework\TestCase
{
    static function setUpBeforeClass()
    {
        $con = new SqliteConnection([
            'prefix' => '',
            'db'     => 'sqlite',
            'path'   => realpath(__DIR__ . '/../../../Localization/Defaults/localization.sqlite'),
        ]);

        DataMapperAbstract::setConnection($con);
    }

    public function testR()
    {
        $obj = CurrencyMapper::get(50);
        self::assertEquals('Euro', $obj->getName());
        self::assertEquals('EUR', $obj->getCode());
        self::assertEquals(978, $obj->getNumber());
        self::assertEquals(2, $obj->getDecimals());
        self::assertContains('Germany', $obj->getCountries());
    }

    static function tearDownAfterClass()
    {
        DataMapperAbstract::setConnection($GLOBALS['dbpool']->get());
    }
}
