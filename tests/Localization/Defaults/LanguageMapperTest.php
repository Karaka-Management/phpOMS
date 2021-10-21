<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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
use phpOMS\Localization\Defaults\LanguageMapper;

/**
 * @testdox phpOMS\tests\Localization\Defaults\LanguageMapperTest: Language database mapper
 *
 * @internal
 */
final class LanguageMapperTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass() : void
    {
        $con = new SqliteConnection([
            'prefix'     => '',
            'db'         => 'sqlite',
            'database'   => \realpath(__DIR__ . '/../../../Localization/Defaults/localization.sqlite'),
        ]);

        DataMapperAbstract::setConnection($con);
    }

    /**
     * @testdox The model can be read from the database
     * @covers phpOMS\Localization\Defaults\LanguageMapper
     * @group framework
     */
    public function testR() : void
    {
        $obj = LanguageMapper::get(53);
        self::assertEquals('German', $obj->getName());
        self::assertEquals('Deutsch', $obj->getNative());
        self::assertEquals('de', $obj->getCode2());
        self::assertEquals('deu', $obj->getCode3Native());
        self::assertEquals('ger', $obj->getCode3());
    }

    public static function tearDownAfterClass() : void
    {
        DataMapperAbstract::setConnection($GLOBALS['dbpool']->get());
    }
}
