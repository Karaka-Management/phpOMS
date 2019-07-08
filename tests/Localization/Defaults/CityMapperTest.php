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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\Localization\Defaults\CityMapper;

/**
 * @internal
 */
class CityMapperTest extends \PHPUnit\Framework\TestCase
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

    public function testR() : void
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

    public static function tearDownAfterClass() : void
    {
        DataMapperAbstract::setConnection($GLOBALS['dbpool']->get());
    }
}
