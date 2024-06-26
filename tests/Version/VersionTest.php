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

namespace phpOMS\tests\Version;

use phpOMS\Version\Version;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Version\Version::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Version\VersionTest: Version handler')]
final class VersionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Versions can be compared with each other')]
    public function testVersionCompare() : void
    {
        $version1 = '1.23.456';
        $version2 = '1.23.567';

        self::assertEquals(Version::compare($version1, $version1), 0);
        self::assertEquals(Version::compare($version1, $version2), -1);
        self::assertEquals(Version::compare($version2, $version1), 1);
    }
}
