<?php
/**
 * Karaka
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

namespace phpOMS\tests\Business\Marketing;

use phpOMS\Business\Marketing\PageRank;

/**
 * @testdox phpOMS\tests\Business\Marketing\PageRankTest: Page rank algorithm
 *
 * @internal
 */
final class PageRankTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Test the correctness of the page rank algorithm
     * @group framework
     */
    public function testPageRank() : void
    {
        $relations = [
            'A' => ['B', 'C'],
            'B' => ['C'],
            'C' => ['A'],
            'D' => ['C'],
        ];

        $ranking = new PageRank($relations, true);

        self::assertEqualsWithDelta(
            [
                'A' => 1.49,
                'B' => 0.78,
                'C' => 1.58,
                'D' => 0.15,
            ],
            $ranking->calculateRanks(20, null),
            0.01
        );
    }

    /**
     * @testdox Test the correctness of the page rank algorithm with custom damping and starting values
     * @group framework
     */
    public function testPageRankCustomDampingAndStart() : void
    {
        $relations = [
            'A' => ['B', 'C'],
            'B' => ['C'],
            'C' => ['A'],
        ];

        $ranking = new PageRank($relations, true, 0.5);

        self::assertEqualsWithDelta(
            [
                'A' => 1.0769,
                'B' => 0.769,
                'C' => 1.1538,
            ],
            $ranking->calculateRanks(20, ['A' => 1.0, 'B' => 1.0, 'C' => 1.0]),
            0.01
        );
    }
}
