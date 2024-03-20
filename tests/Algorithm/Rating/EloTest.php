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

namespace phpOMS\tests\Algorithm\Rating;

use phpOMS\Algorithm\Rating\Elo;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\Rating\EloTest: Rating generation
 *
 * @internal
 */
final class EloTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox 1v1 rating test
     * @covers \phpOMS\Algorithm\Rating\Elo
     * @group framework
     */
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

    /**
     * @testdox group rating test
     * @covers \phpOMS\Algorithm\Rating\Elo
     * @group framework
     */
    public function testGroupRating() : void
    {
        $rating = new Elo();

        self::assertEquals(
            ['elo' => 1500 + 18 + 4 + 14 - 16],
            $rating->rating(1500, [1550, 1600, 1450, 1500], [1.0, 0.5, 1.0, 0.0])
        );
    }
}
