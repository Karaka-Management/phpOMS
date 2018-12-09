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

use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\Localization\Defaults\CountryMapper;

class CountryMapperTest extends \PHPUnit\Framework\TestCase
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
        $obj = CountryMapper::get(83);
        self::assertEquals('Germany', $obj->getName());
        self::assertEquals('DE', $obj->getCode2());
        self::assertEquals('DEU', $obj->getCode3());
        self::assertEquals(276, $obj->getNumeric());
        self::assertEquals('ISO 3166-2:DE', $obj->getSubdevision());
    }

    static function tearDownAfterClass()
    {
        DataMapperAbstract::setConnection($GLOBALS['dbpool']->get());
    }
}
