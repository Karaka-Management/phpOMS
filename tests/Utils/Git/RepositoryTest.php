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

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Repository;

/**
 * @testdox phpOMS\tests\Utils\Git\RepositoryTest: Git repository
 *
 * @internal
 * @todo create tests for other functions
 */
class RepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The repository has the expected default values after initialization
     * @covers phpOMS\Utils\Git\Repository
     * @group framework
     */
    public function testDefault() : void
    {
        $repo = new Repository(\realpath(__DIR__ . '/../../../'));
        self::assertTrue('phpOMS' === $repo->getName() || 'build' === $repo->getName());
        self::assertEquals(\str_replace('\\', '/', \realpath(__DIR__ . '/../../../.git')), \str_replace('\\', '/', $repo->getDirectoryPath()));
        self::assertEquals(\realpath(__DIR__ . '/../../../'), $repo->getPath());
    }
}
