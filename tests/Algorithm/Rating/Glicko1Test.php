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

namespace phpOMS\tests\Algorithm\Rating;

use phpOMS\Algorithm\Rating\Glicko1;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\Rating\Glicko1::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\Rating\Glicko1Test: Rating generation')]
final class Glicko1Test extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('1v1 rating test')]
    public function testSoloRating() : void
    {
        $rating = new Glicko1();

        // large difference (no equal exchange due to RD)
        self::assertEquals(
            [
                'elo' => 1648,
                'rd'  => 186,
            ],
            $rating->rating(1500, 200, 0, 0, [1800], [1.0], [150])
        );

        self::assertEquals(
            [
                'elo' => 1717,
                'rd'  => 144,
            ],
            $rating->rating(1800, 150, 0, 0, [1500], [0.0], [200])
        );

        // large difference (equal exchange due to same RD)
        self::assertEquals(
            [
                'elo' => 1637,
                'rd'  => 186,
            ],
            $rating->rating(1500, 200, 0, 0, [1800], [1.0], [200])
        );

        self::assertEquals(
            [
                'elo' => 1662,
                'rd'  => 186,
            ],
            $rating->rating(1800, 200, 0, 0, [1500], [0.0], [200])
        );

        // similar win
        self::assertEquals(
            [
                'elo' => 1621,
                'rd'  => 177,
            ],
            $rating->rating(1550, 200, 0, 0, [1500], [1.0], [150])
        );

        self::assertEquals(
            [
                'elo' => 1428,
                'rd'  => 177,
            ],
            $rating->rating(1500, 200, 0, 0, [1550], [0.0], [150])
        );

        // similar draw
        self::assertEquals(
            [
                'elo' => 1539,
                'rd'  => 177,
            ],
            $rating->rating(1550, 200, 0, 0, [1500], [0.5], [150])
        );

        self::assertEquals(
            [
                'elo' => 1510,
                'rd'  => 177,
            ],
            $rating->rating(1500, 200, 0, 0, [1550], [0.5], [150])
        );

        // very large difference win
        self::assertEquals(
            [
                'elo' => 2401,
                'rd'  => 199,
            ],
            $rating->rating(2400, 200, 0, 0, [1500], [1.0], [150])
        );

        self::assertEquals(
            [
                'elo' => 1498,
                'rd'  => 199,
            ],
            $rating->rating(1500, 200, 0, 0, [2400], [0.0], [150])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('group rating test')]
    public function testGroupRating() : void
    {
        $rating = new Glicko1();

        self::assertEquals(
            [
                'elo' => 1464,
                'rd'  => 151,
            ],
            $rating->rating(1500, 200, 0, 0, [1400, 1550, 1700], [1.0, 0.0, 0.0], [30, 100, 300])
        );
    }
}
