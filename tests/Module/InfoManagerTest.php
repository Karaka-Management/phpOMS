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

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Module\InfoManager;

class InfoManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testInfoManager() : void
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

        $info->set('/name/internal', 'ABC');
        self::assertEquals('ABC', $info->getInternalName());
        $info->update();

        $info2 = new InfoManager(__DIR__ . '/info-test.json');
        $info2->load();
        self::assertEquals($info->getInternalName(), $info2->getInternalName());

        $info->set('/name/internal', $jarray['name']['internal']);
        $info->update();
    }

    public function testInvalidPathLoad() : void
    {
        self::expectedException(\phpOMS\System\File\PathException::class);

        $info = new InfoManager(__DIR__ . '/invalid.json');
        $info->load();
    }

    public function testInvalidPathUpdate() : void
    {
        self::expectedException(\phpOMS\System\File\PathException::class);

        $info = new InfoManager(__DIR__ . '/invalid.json');
        $info->update();
    }

    public function testInvalidDataSet() : void
    {
        self::expectedException(\InvalidArgumentException::class);

        $info = new InfoManager(__DIR__ . '/info-test.json');
        $info->load();

        $testObj = new class {
            public $test = 1;

            public function test() : void
            {
                echo $this->test;
            }
        };

        $info->set('/name/internal', $testObj);
    }
}
