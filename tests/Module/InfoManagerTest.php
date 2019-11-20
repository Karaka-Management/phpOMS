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

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Module\InfoManager;

/**
 * @testdox phpOMS\tests\Module\InfoManagerTest: Module info file manager
 *
 * @internal
 */
class InfoManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A info file can be correctly loaded
     * @covers phpOMS\Module\InfoManager
     */
    public function testLoad() : void
    {
        $info = new InfoManager(__DIR__ . '/info-test.json');
        $info->load();

        $jarray = \json_decode(\file_get_contents(__DIR__ . '/info-test.json'), true);

        self::assertEquals($jarray, $info->get());
        self::assertEquals($jarray['name']['id'], $info->getId());
        self::assertEquals($jarray['name']['internal'], $info->getInternalName());
        self::assertEquals($jarray['name']['external'], $info->getExternalName());
        self::assertEquals($jarray['category'], $info->getCategory());
        self::assertEquals($jarray['dependencies'], $info->getDependencies());
        self::assertEquals($jarray['providing'], $info->getProviding());
        self::assertEquals($jarray['directory'], $info->getDirectory());
        self::assertEquals($jarray['version'], $info->getVersion());
        self::assertEquals($jarray['load'], $info->getLoad());
        self::assertEquals(__DIR__ . '/info-test.json', $info->getPath());
    }

    /**
     * @testdox A info file can be modified
     * @covers phpOMS\Module\InfoManager
     */
    public function testChange() : void
    {
        $jarray = \json_decode(\file_get_contents(__DIR__ . '/info-test.json'), true);

        $info = new InfoManager(__DIR__ . '/info-test.json');
        $info->load();

        $info->set('/name/internal', 'ABC');
        self::assertEquals('ABC', $info->getInternalName());
        $info->update();

        $info2 = new InfoManager(__DIR__ . '/info-test.json');
        $info2->load();
        self::assertEquals($info->getInternalName(), $info2->getInternalName());

        $info->set('/name/internal', $jarray['name']['internal']);
        $info->update();
    }

    /**
     * @testdox A invalid info file path load throws a PathException
     * @covers phpOMS\Module\InfoManager
     */
    public function testInvalidPathLoad() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        $info = new InfoManager(__DIR__ . '/invalid.json');
        $info->load();
    }

    /**
     * @testdox A invalid info file path update throws a PathException
     * @covers phpOMS\Module\InfoManager
     */
    public function testInvalidPathUpdate() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        $info = new InfoManager(__DIR__ . '/invalid.json');
        $info->update();
    }

    /**
     * @testdox A invalid change data throws a InvalidArgumentException
     * @covers phpOMS\Module\InfoManager
     */
    public function testInvalidDataSet() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $info = new InfoManager(__DIR__ . '/info-test.json');
        $info->load();

        $testObj = new class() {
            public $test = 1;

            public function test() : void
            {
                echo $this->test;
            }
        };

        $info->set('/name/internal', $testObj);
    }
}
