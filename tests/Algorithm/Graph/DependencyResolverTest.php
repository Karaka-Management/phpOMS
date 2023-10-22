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
 * @testdox phpOMS\tests\Algorithm\Graph\DependencyResolverTest:
 *
 * @internal
 */
final class DependencyResolverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A value is matched with the minimum quantity of available coins.
     * @covers phpOMS\Algorithm\Graph\DependencyResolver
     * @group framework
     */
    public function testResolve() : void
    {
        self::assertEquals(
            null,
            DependencyResolver::resolve([0 => [1, 2], 1 => [0, 2], 2 => []])
        );

        self::assertEquals(
            [0, 1, 2, 3],
            DependencyResolver::resolve([0 => [1, 2], 1 => [2, 3], 2 => [], 3 => []])
        );
    }
}
