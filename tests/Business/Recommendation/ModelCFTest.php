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

namespace phpOMS\tests\Business\Recommendation;

use phpOMS\Business\Recommendation\ModelCF;

/**
 * @testdox phpOMS\tests\Business\Recommendation\ModelCFTest: Article affinity/correlation
 *
 * @internal
 */
final class ModelCFTest extends \PHPUnit\Framework\TestCase
{
    public function testScore() : void
    {
        self::assertEquals(
            [
                [],
                [],
                [],
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
