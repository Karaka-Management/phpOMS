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

use phpOMS\Business\Recommendation\ModelCF;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Business\Recommendation\ModelCFTest: Article affinity/correlation')]
final class ModelCFTest extends \PHPUnit\Framework\TestCase
{
    public function testScore() : void
    {
        self::assertEquals(
            [
                [14.0, 12.0, 10.0],
                [17.0, 11.0, 10.0],
                [15.0, 15.0, 12.0],
            ],
            ModelCF::score(
                [
                    [2, 3],
                    [1, 4],
                    [3, 3],
                ],
                [
                    [1, 4],
                    [3, 2],
                    [2, 2],
                ]
            )
        );
    }
}
