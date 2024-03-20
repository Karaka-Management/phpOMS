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

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Module\ModuleInfo;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Module\ModuleInfo::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Module\ModuleInfoTest: Module info file manager')]
final class ModuleInfoTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A info file can be correctly loaded')]
    public function testLoad() : void
    {
        $info = new ModuleInfo(__DIR__ . '/info-test.json');
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A info file can be modified')]
    public function testChange() : void
    {
        $jarray = \json_decode(\file_get_contents(__DIR__ . '/info-test.json'), true);

        $info = new ModuleInfo(__DIR__ . '/info-test.json');
        $info->load();

        $info->set('/name/internal', 'ABC');
        self::assertEquals('ABC', $info->getInternalName());
        $info->update();

        $info2 = new ModuleInfo(__DIR__ . '/info-test.json');
        $info2->load();
        self::assertEquals($info->getInternalName(), $info2->getInternalName());

        $info->set('/name/internal', $jarray['name']['internal']);
        $info->update();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid info file path load throws a PathException')]
    public function testInvalidPathLoad() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        $info = new ModuleInfo(__DIR__ . '/invalid.json');
        $info->load();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid info file path update throws a PathException')]
    public function testInvalidPathUpdate() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        $info = new ModuleInfo(__DIR__ . '/invalid.json');
        $info->update();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid change data throws a InvalidArgumentException')]
    public function testInvalidDataSet() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $info = new ModuleInfo(__DIR__ . '/info-test.json');
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
