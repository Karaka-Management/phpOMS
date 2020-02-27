<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\Maze
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */

declare(strict_types=1);

namespace phpOMS\Algorithm\Maze;

/**
 * Maze generator
 *
 * @package phpOMS\Algorithm\Maze
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class MazeGenerator
{
    /**
     * Constructor
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Generate a random maze
     *
     * @param int $width  Width
     * @param int $height Height
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function random(int $width, int $height) : array
    {
        $n          = $height * $width - 1;
        $horizontal = \array_fill(0, $height, []);
        $vertical   = \array_fill(0, $height, []);

        $pos       = [\mt_rand(0, $height) - 1, \mt_rand(0, $width) - 1];
        $path      = [$pos];
        $unvisited = [];

        for ($i = 0; $i < $height + 2; ++$i) {
            $unvisited[] = [];

            for ($j = 0; $j < $width + 1; ++$j) {
                $unvisited[$i][] = $i > 0 && $i < $height + 1 && $j > 0 && ($i !== $pos[0] + 1 || $j != $pos[1] + 1);
            }
        }

        while (0 < $n) {
            $potential = [
                [$pos[0] + 1, $pos[1]],
                [$pos[0], $pos[1] + 1],
                [$pos[0] - 1, $pos[1]],
                [$pos[0], $pos[1] - 1],
            ];

            $neighbors = [];

            for ($i = 0; $i < 4; ++$i) {
                if ($unvisited[$potential[$i][0] + 1][$potential[$i][1] + 1] ?? false) {
                    $neighbors[] = $potential[$i];
                }
            }

            if (!empty($neighbors)) {
                --$n;

                $next                                  = $neighbors[\mt_rand(0, \count($neighbors) - 1)];
                $unvisited[$next[0] + 1][$next[1] + 1] = false;

                if ($next[0] === $pos[0]) {
                    $horizontal[$next[0]][($next[1] + $pos[1] - 1) / 2] = true;
                } else {
                    $vertical[($next[0] + $pos[0] - 1) / 2][$next[1]] = true;
                }

                $path[] = $next;
                $pos    = $next;
            } else {
                $pos = \array_pop($path);

                if ($pos === null) {
                    $n = 0;
                    break;
                }
            }
        }

        $maze = [];
        for ($i = 0; $i < $height * 2 + 1; ++$i) {
            $line = [];

            if ($i % 2 === 0) {
                for ($j = 0; $j < $width * 4 + 1; ++$j) {
                    if ($j % 4 === 0) {
                        $line[$j] = '+'; // 9
                    } else {
                        $line[$j] = $i > 0 && ($vertical[$i / 2 - 1][(int) \floor($j / 4)] ?? false) ? ' ' : '-'; // 9
                    }
                }
            } else {
                for ($j = 0; $j < $width * 4 + 1; ++$j) { // 2
                    if ($j % 4 === 0) {
                        $line[$j] = $j > 0 && ($horizontal[($i - 1) / 2][$j / 4 - 1] ?? false) ? ' ' : '|'; // 0 | 9
                    } else {
                        $line[$j] = ' '; // 0
                    }
                }
            }

            if ($i === 0) {
                $line[1] = $line[2] = $line[3] = ' '; // 0
            }

            if ($height * 2 - 1 === $i) {
                $line[4 * $width] = ' '; // 2 - 0
            }

            $maze[] = $line;
        }

        return $maze;
    }

    /**
     * Render a maze
     *
     * @param array<int, int[]> $maze Maze to render
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function render(array $maze) : string
    {
        $rendered = '';
        foreach ($maze as $y => $row) {
            foreach ($row as $x => $column) {
                $rendered .= $column;
            }

            $rendered .= "\n";
        }

        return $rendered;
    }
}
