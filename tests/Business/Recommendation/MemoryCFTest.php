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

namespace phpOMS\tests\Business\Recommendation;

use phpOMS\Business\Recommendation\MemoryCF;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Business\Recommendation\MemoryCFTest: Article affinity/correlation')]
final class MemoryCFTest extends \PHPUnit\Framework\TestCase
{
    public function testBestMatch() : void
    {
        $memory = new MemoryCF([
            'A' => [1.0, 2.0],
            'B' => [2.0, 4.0],
            'C' => [2.5, 4.0],
            'D' => [4.5, 5.0],
        ]);

        self::assertEquals(
            ['B', 'C'],
            $memory->bestMatch([2.2, 4.1], 2)
        );
    }
}
