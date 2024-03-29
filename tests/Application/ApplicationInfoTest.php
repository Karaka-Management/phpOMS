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

namespace phpOMS\tests\Application;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Application\ApplicationInfo;

/**
 * @testdox phpOMS\tests\Application\ApplicationInfoTest: Module info file manager
 *
 * @internal
 */
final class ApplicationInfoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox An application info file can be correctly loaded
     * @covers phpOMS\Application\ApplicationInfo
     * @group framework
     */
    public function testLoad() : void
    {
        $info = new ApplicationInfo(__DIR__ . '/info-test.json');
        $info->load();

        $jarray = \json_decode(\file_get_contents(__DIR__ . '/info-test.json'), true);

        self::assertEquals($jarray, $info->get());
        self::assertEquals($jarray['name']['id'], $info->getId());
        self::assertEquals($jarray['name']['internal'], $info->getInternalName());
        self::assertEquals($jarray['name']['external'], $info->getExternalName());
        self::assertEquals($jarray['category'], $info->getCategory());
        self::assertEquals($jarray['dependencies'], $info->getDependencies());
        self::assertEquals($jarray['directory'], $info->getDirectory());
        self::assertEquals($jarray['version'], $info->getVersion());
        self::assertEquals($jarray['providing'], $info->getProviding());
        self::assertEquals(__DIR__ . '/info-test.json', $info->getPath());
    }

    /**
     * @testdox A info file can be modified
     * @covers phpOMS\Application\ApplicationInfo
     * @group framework
     */
    public function testChange() : void
    {
        $jarray = \json_decode(\file_get_contents(__DIR__ . '/info-test.json'), true);

        $info = new ApplicationInfo(__DIR__ . '/info-test.json');
        $info->load();

        $info->set('/name/internal', 'Testapp');
        self::assertEquals('Testapp', $info->getInternalName());
        $info->update();

        $info2 = new ApplicationInfo(__DIR__ . '/info-test.json');
        $info2->load();
        self::assertEquals($info->getInternalName(), $info2->getInternalName());

        $info->set('/name/internal', $jarray['name']['internal']);
        $info->update();
    }

    /**
     * @testdox A invalid info file path load throws a PathException
     * @covers phpOMS\Application\ApplicationInfo
     * @group framework
     */
    public function testInvalidPathLoad() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        $info = new ApplicationInfo(__DIR__ . '/invalid.json');
        $info->load();
    }

    /**
     * @testdox A invalid info file path update throws a PathException
     * @covers phpOMS\Application\ApplicationInfo
     * @group framework
     */
    public function testInvalidPathUpdate() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        $info = new ApplicationInfo(__DIR__ . '/invalid.json');
        $info->update();
    }

    /**
     * @testdox A invalid change data throws a InvalidArgumentException
     * @covers phpOMS\Application\ApplicationInfo
     * @group framework
     */
    public function testInvalidDataSet() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $info = new ApplicationInfo(__DIR__ . '/info-test.json');
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
