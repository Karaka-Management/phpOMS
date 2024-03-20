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

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Config\OptionsTrait;
use phpOMS\Config\SettingsInterface;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Module\InstallerAbstract::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Module\InstallerAbstractTest: Abstract module installer')]
final class InstallerAbstractTest extends \PHPUnit\Framework\TestCase
{
	protected InstallerAbstract $installer;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
    	$this->installer = new class() extends InstallerAbstract
    	{
        };
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid or missing module status file throws exception during installation')]
    public function testInvalidModuleInstall() : void
    {
        $this->expectException(\UnexpectedValueException::class);

        $app         = new class() extends ApplicationAbstract {};
        $app->dbPool = $GLOBALS['dbpool'];

    	$this->installer::install(
    		$app,
    		new ModuleInfo(__DIR__),
    		new class () implements SettingsInterface {
                use OptionsTrait;

                public function get(
                    mixed $ids = null,
                    string | array|null $names = null,
                    ?int $unit = null,
                    ?int $app = null,
                    ?string $module = null,
                    ?int $group = null,
                    ?int $account = null
                ) : mixed
                {
                    return null;
                }

                public function set(array $options, bool $store = false) : void
                {}

                public function save(array $options = []) : void
                {}

                public function create(array $options = []) : void
                {}
            }
    	);
    }
}
