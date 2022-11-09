<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Business\Marketing;

use phpOMS\Business\Marketing\ArticleCorrelationAffinity;

/**
 * @testdox phpOMS\tests\Business\Marketing\ArticleCorrelationAffinityTest: Article affinity/correlation
 *
 * @internal
 */
final class ArticleCorrelationAffinityTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The highest affinities between articles purchased are calculated correctly
     * @group framework
     */
    public function testAffinity() : void
    {
        $orders = [
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 0, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 0, 'C' => 0, 'D' => 1],
            ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 0, 'C' => 1, 'D' => 0],
            ['A' => 0, 'B' => 0, 'C' => 1, 'D' => 0],
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 0, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 0, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0],
            ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0],
        ];

        $aff = new ArticleCorrelationAffinity($orders);

        self::assertEqualsWithDelta(
            [
                'A' => 0.3273,
                'C' => -0.4803,
                'D' => -0.3273,
            ],
            $aff->getAffinity('B'),
            0.001
        );

        self::assertEqualsWithDelta(
            [
                'A' => 0.3273,
                'D' => -0.3273,
            ],
            $aff->getAffinity('B', 2),
            0.001
        );
    }

    /**
     * @testdox The affinity of a new article is empty
     * @group framework
     */
    public function testInvalidItemAffinity() : void
    {
        $orders = [
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 0, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 0, 'C' => 0, 'D' => 1],
            ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 0, 'C' => 1, 'D' => 0],
            ['A' => 0, 'B' => 0, 'C' => 1, 'D' => 0],
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 0, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 0, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 1, 'B' => 1, 'C' => 0, 'D' => 0],
            ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0],
            ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0],
        ];

        $aff = new ArticleCorrelationAffinity($orders);

        self::assertEquals([], $aff->getAffinity('Z'));
    }
}
