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

use phpOMS\Localization\Defaults\Iban;
use phpOMS\Localization\Defaults\IbanMapper;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\DataStorage\Database\Connection\SQLiteConnection;

class IbanMapperTest extends \PHPUnit\Framework\TestCase
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
        $obj = IbanMapper::get(22);
        self::assertEquals('DE', $obj->getCountry());
        self::assertEquals(22, $obj->getChars());
        self::assertEquals('18n', $obj->getBban());
        self::assertEquals('DEkk bbbb bbbb cccc cccc cc', $obj->getFields());
    }

    static function tearDownAfterClass()
    {
        DataMapperAbstract::setConnection($GLOBALS['dbpool']->get());
    }
}
