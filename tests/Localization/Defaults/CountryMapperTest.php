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
use phpOMS\Localization\Defaults\CountryMapper;

/**
 * @testdox phpOMS\tests\Localization\Defaults\CountryMapperTest: Country database mapper
 *
 * @internal
 */
class CountryMapperTest extends \PHPUnit\Framework\TestCase
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
        $obj = CountryMapper::get(83);
        self::assertEquals('Germany', $obj->getName());
        self::assertEquals('DE', $obj->getCode2());
        self::assertEquals('DEU', $obj->getCode3());
        self::assertEquals(276, $obj->getNumeric());
        self::assertEquals('ISO 3166-2:DE', $obj->getSubdevision());
    }

    public static function tearDownAfterClass() : void
    {
        DataMapperAbstract::setConnection($GLOBALS['dbpool']->get());
    }
}
