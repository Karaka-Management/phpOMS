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

use phpOMS\Algorithm\Rating\Elo;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\Rating\Elo::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\Rating\EloTest: Rating generation')]
final class EloTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('1v1 rating test')]
    public function testSoloRating() : void
    {
        $rating = new Elo();

        // large difference win
        self::assertEquals(
            ['elo' => 1805],
            $rating->rating(1800, [1500], [1.0])
        );

        self::assertEquals(
            ['elo' => 1495],
            $rating->rating(1500, [1800], [0.0])
        );

        // similar win
        self::assertEquals(
            ['elo' => 1564],
            $rating->rating(1550, [1500], [1.0])
        );

        self::assertEquals(
            ['elo' => 1486],
            $rating->rating(1500, [1550], [0.0])
        );

        // similar draw
        self::assertEquals(
            ['elo' => 1548],
            $rating->rating(1550, [1500], [0.5])
        );

        self::assertEquals(
            ['elo' => 1502],
            $rating->rating(1500, [1550], [0.5])
        );

        // very large difference win
        self::assertEquals(
            ['elo' => 2400],
            $rating->rating(2400, [1500], [1.0])
        );

        self::assertEquals(
            ['elo' => 1500],
            $rating->rating(1500, [2400], [0.0])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('group rating test')]
    public function testGroupRating() : void
    {
        $rating = new Elo();

        self::assertEquals(
            ['elo' => 1500 + 18 + 4 + 14 - 16],
            $rating->rating(1500, [1550, 1600, 1450, 1500], [1.0, 0.5, 1.0, 0.0])
        );
    }
}
