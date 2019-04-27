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
 declare(strict_types=1);

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Repository;

/**
 * @internal
 */
class RepositoryTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $repo = new Repository(\realpath(__DIR__ . '/../../../'));
        self::assertTrue('phpOMS' === $repo->getName() || 'build' === $repo->getName());
        self::assertEquals(\str_replace('\\', '/', \realpath(__DIR__ . '/../../../.git')), \str_replace('\\', '/', $repo->getDirectoryPath()));
        self::assertEquals(\realpath(__DIR__ . '/../../../'), $repo->getPath());
    }
}
