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
use phpOMS\Localization\Defaults\Iban;
use phpOMS\Localization\Defaults\IbanMapper;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Localization\Defaults\IbanMapper::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\Defaults\IbanMapperTest: Iban database mapper')]
final class IbanMapperTest extends \PHPUnit\Framework\TestCase
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
        /** @var Iban $obj */
        $obj = IbanMapper::get()->where('id', 22)->execute();
        self::assertEquals('DE', $obj->country);
        self::assertEquals(22, $obj->getChars());
        self::assertEquals('18n', $obj->getBban());
        self::assertEquals('DEkk bbbb bbbb cccc cccc cc', $obj->getFields());
    }

    public static function tearDownAfterClass() : void
    {
        self::$con->close();
        DataMapperFactory::db($GLOBALS['dbpool']->get());
    }
}
