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

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Repository;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Git\Repository::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Git\RepositoryTest: Git repository')]
final class RepositoryTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The repository has the expected default values after initialization')]
    public function testDefault() : void
    {
        $repo = new Repository(\realpath(__DIR__ . '/../../../'));
        self::assertTrue($repo->getName() === 'phpOMS' || $repo->getName() === 'build');
        self::assertEquals(\strtr(\realpath(__DIR__ . '/../../../.git'), '\\', '/'), \strtr($repo->getDirectoryPath(), '\\', '/'));
        self::assertEquals(\realpath(__DIR__ . '/../../../'), $repo->getPath());
    }
}
