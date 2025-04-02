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

namespace phpOMS\tests\Algorithm\Rating;

use phpOMS\Algorithm\Rating\Glicko2;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\Rating\Glicko2::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\Rating\Glicko2Test: Rating generation')]
final class Glicko2Test extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('1v1 rating test')]
    public function testSoloRating() : void
    {
        $rating = new Glicko2();

        // large difference (no equal exchange due to RD)
        self::assertEqualsWithDelta(
            [
                'elo' => 1649,
                'rd'  => 186,
                'vol' => 0.06,
            ],
            $rating->rating(1500, 200, 0.06, [1800], [1.0], [150]),
            0.0001
        );

        self::assertEqualsWithDelta(
            [
                'elo' => 1717,
                'rd'  => 144,
                'vol' => 0.06,
            ],
            $rating->rating(1800, 150, 0.06, [1500], [0.0], [200]),
            0.0001
        );

        // large difference (equal exchange due to same RD)
        self::assertEqualsWithDelta(
            [
                'elo' => 1638,
                'rd'  => 187,
                'vol' => 0.06,
            ],
            $rating->rating(1500, 200, 0.06, [1800], [1.0], [200]),
            0.0001
        );

        self::assertEqualsWithDelta(
            [
                'elo' => 1661,
                'rd'  => 187,
                'vol' => 0.06,
            ],
            $rating->rating(1800, 200, 0.06, [1500], [0.0], [200]),
            0.0001
        );

        // similar win
        self::assertEqualsWithDelta(
            [
                'elo' => 1621,
                'rd'  => 177,
                'vol' => 0.06,
            ],
            $rating->rating(1550, 200, 0.06, [1500], [1.0], [150]),
            0.0001
        );

        self::assertEqualsWithDelta(
            [
                'elo' => 1428,
                'rd'  => 177,
                'vol' => 0.06,
            ],
            $rating->rating(1500, 200, 0.06, [1550], [0.0], [150]),
            0.0001
        );

        // similar draw
        self::assertEqualsWithDelta(
            [
                'elo' => 1539,
                'rd'  => 177,
                'vol' => 0.06,
            ],
            $rating->rating(1550, 200, 0.06, [1500], [0.5], [150]),
            0.0001
        );

        self::assertEqualsWithDelta(
            [
                'elo' => 1510,
                'rd'  => 177,
                'vol' => 0.06,
            ],
            $rating->rating(1500, 200, 0.06, [1550], [0.5], [150]),
            0.0001
        );

        // very large difference win
        self::assertEqualsWithDelta(
            [
                'elo' => 2401,
                'rd'  => 199,
                'vol' => 0.06,
            ],
            $rating->rating(2400, 200, 0.06, [1500], [1.0], [150]),
            0.0001
        );

        self::assertEqualsWithDelta(
            [
                'elo' => 1498,
                'rd'  => 199,
                'vol' => 0.06,
            ],
            $rating->rating(1500, 200, 0.06, [2400], [0.0], [150]),
            0.0001
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('group rating test')]
    public function testGroupRating() : void
    {
        $rating = new Glicko2();

        self::assertEqualsWithDelta(
            [
                'elo' => 1464,
                'rd'  => 151,
                'vol' => 0.059999,
            ],
            $rating->rating(1500, 200, 0.06, [1400, 1550, 1700], [1.0, 0.0, 0.0], [30, 100, 300]),
            0.0001
        );
    }
}
