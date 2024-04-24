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

namespace phpOMS\tests\Algorithm\Maze;

use phpOMS\Algorithm\Maze\MazeGenerator;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\Maze\MazeGenerator::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\Maze\MazeGeneratorTest: Maze generation')]
final class MazeGeneratorTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A random maze can be generated')]
    public function testMazeGeneration() : void
    {
        $maze = MazeGenerator::random(10, 7);

        // correct top
        self::assertEquals(
            ['+', ' ', ' ', ' ', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+'],
            $maze[0]
        );

        // correct bottom
        self::assertEquals(
            ['+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+', '-', '-', '-', '+'],
            $maze[14]
        );

        // correct left
        self::assertEquals(
            ['+', '|', '+', '|', '+', '|', '+', '|', '+', '|', '+', '|', '+', '|', '+'],
            [$maze[0][0], $maze[1][0], $maze[2][0], $maze[3][0], $maze[4][0], $maze[5][0], $maze[6][0], $maze[7][0], $maze[8][0], $maze[9][0], $maze[10][0], $maze[11][0], $maze[12][0], $maze[13][0], $maze[14][0]]
        );

        // correct right
        self::assertEquals(
            ['+', '|', '+', '|', '+', '|', '+', '|', '+', '|', '+', '|', '+', ' ', '+'],
            [$maze[0][40], $maze[1][40], $maze[2][40], $maze[3][40], $maze[4][40], $maze[5][40], $maze[6][40], $maze[7][40], $maze[8][40], $maze[9][40], $maze[10][40], $maze[11][40], $maze[12][40], $maze[13][40], $maze[14][40]]
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A random maze can be rendered')]
    public function testMazeRender() : void
    {
        $ob   = MazeGenerator::render(MazeGenerator::random(10, 7));
        $maze = \explode("\n", $ob);

        // correct top
        self::assertEquals(
            '+   +---+---+---+---+---+---+---+---+---+',
            $maze[0]
        );

        // correct bottom
        self::assertEquals(
            '+---+---+---+---+---+---+---+---+---+---+',
            $maze[14]
        );

        // correct left
        self::assertEquals(
            ['+', '|', '+', '|', '+', '|', '+', '|', '+', '|', '+', '|', '+', '|', '+'],
            [$maze[0][0], $maze[1][0], $maze[2][0], $maze[3][0], $maze[4][0], $maze[5][0], $maze[6][0], $maze[7][0], $maze[8][0], $maze[9][0], $maze[10][0], $maze[11][0], $maze[12][0], $maze[13][0], $maze[14][0]]
        );

        // correct right
        self::assertEquals(
            ['+', '|', '+', '|', '+', '|', '+', '|', '+', '|', '+', '|', '+', ' ', '+'],
            [$maze[0][40], $maze[1][40], $maze[2][40], $maze[3][40], $maze[4][40], $maze[5][40], $maze[6][40], $maze[7][40], $maze[8][40], $maze[9][40], $maze[10][40], $maze[11][40], $maze[12][40], $maze[13][40], $maze[14][40]]
        );
    }
}
