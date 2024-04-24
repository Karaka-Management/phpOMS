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
use phpOMS\Localization\Defaults\Language;
use phpOMS\Localization\Defaults\LanguageMapper;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Localization\Defaults\LanguageMapper::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\Defaults\LanguageMapperTest: Language database mapper')]
final class LanguageMapperTest extends \PHPUnit\Framework\TestCase
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
        /** @var Language $obj */
        $obj = LanguageMapper::get()->where('id', 53)->execute();
        self::assertEquals('German', $obj->getName());
        self::assertEquals('Deutsch', $obj->getNative());
        self::assertEquals('de', $obj->getCode2());
        self::assertEquals('deu', $obj->getCode3Native());
        self::assertEquals('ger', $obj->getCode3());
    }

    public static function tearDownAfterClass() : void
    {
        self::$con->close();
        DataMapperFactory::db($GLOBALS['dbpool']->get());
    }
}
