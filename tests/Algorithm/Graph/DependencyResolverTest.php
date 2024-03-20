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

namespace phpOMS\tests\Algorithm\Graph;

use phpOMS\Algorithm\Graph\DependencyResolver;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\Graph\DependencyResolver::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\Graph\DependencyResolverTest:')]
final class DependencyResolverTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testResolveCircular() : void
    {
        self::assertNull(
            DependencyResolver::resolve([0 => [1, 2], 1 => [0, 2], 2 => []])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testResolve() : void
    {
        self::assertEquals(
            [2, 3, 1, 0],
            DependencyResolver::resolve([0 => [1, 2], 1 => [2, 3], 2 => [], 3 => []])
        );
    }
}
