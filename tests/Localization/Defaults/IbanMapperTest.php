<?php
/**
 * Jingga
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
use phpOMS\Localization\Defaults\Iban;
use phpOMS\Localization\Defaults\IbanMapper;

/**
 * @testdox phpOMS\tests\Localization\Defaults\IbanMapperTest: Iban database mapper
 *
 * @internal
 */
final class IbanMapperTest extends \PHPUnit\Framework\TestCase
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
     * @covers phpOMS\Localization\Defaults\IbanMapper
     * @group framework
     */
    public function testR() : void
    {
        /** @var Iban $obj */
        $obj = IbanMapper::get()->where('id', 22)->execute();
        self::assertEquals('DE', $obj->getCountry());
        self::assertEquals(22, $obj->getChars());
        self::assertEquals('18n', $obj->getBban());
        self::assertEquals('DEkk bbbb bbbb cccc cccc cc', $obj->getFields());
    }

    public static function tearDownAfterClass() : void
    {
        DataMapperFactory::db($GLOBALS['dbpool']->get());
    }
}
