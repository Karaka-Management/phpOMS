<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Business\Recommendation;

use phpOMS\Business\Recommendation\ArticleCorrelationAffinity;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Business\Recommendation\ArticleCorrelationAffinityTest: Article affinity/correlation')]
final class ArticleCorrelationAffinityTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The highest affinities between articles purchased are calculated correctly')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The affinity of a new article is empty')]
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
