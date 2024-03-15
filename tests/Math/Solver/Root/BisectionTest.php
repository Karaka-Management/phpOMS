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

namespace phpOMS\tests\Math\Solver\Root;

use phpOMS\Math\Solver\Root\Bisection;

/**
 * @testdox phpOMS\tests\Math\Solver\Root\BisectionTest: Various math functions
 *
 * @internal
 */
final class BisectionTest extends \PHPUnit\Framework\TestCase
{
    public function testRoot() : void
    {
        self::assertEqualsWithDelta(
            1.521,
            Bisection::root(function($x) { return $x * $x * $x - $x - 2; }, 1, 2),
            0.1
        );
    }
}
