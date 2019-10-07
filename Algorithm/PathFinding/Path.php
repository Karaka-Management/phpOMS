<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\PathFinding
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\PathFinding;

/**
 * Path in grids.
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Path
{
    public array $nodes = [];
    private float $weight = 0.0;
    private float $distance = 0.0;
    private Grid $grid;

    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    public function addNode(Node $node) : void
    {
        $this->nodes[] = $node;
    }

    public function expandPath() : array
    {
        $reverse = \array_reverse($this->nodes);
        $length  = \count($reverse);

        if ($length < 2) {
            return $reverse;
        }

        $expanded = [];
        for ($i = 0; $i < $length - 1; ++$i) {
            $coord0 = $reverse[$i];
            $coord1 = $reverse[$i + 1];

            $interpolated = $this->interpolate($coord0, $coord1);
            $iLength      = \count($interpolated);

            $expanded = \array_merge($expanded, \array_slice($interpolated, 0, $iLength - 1));
        }

        $expanded[] = $reverse[$length - 1];

        return $expanded;
    }

    private function interpolate(Node $node1, Node $node2) : array
    {
        $dx = \abs($node2->getX() - $node1->getX());
        $dy = \abs($node2->getY() - $node1->getY());

        $sx = ($node1->getX() < $node2->getX()) ? 1 : -1;
        $sy = ($node1->getY() < $node2->getY()) ? 1 : -1;

        $node = $node1;
        $err  = $dx - $dy;

        $line = [];
        while (true) {
            $line[] = $node;

            if ($node->getX() === $node2->getX() && $node->getY() === $node2->getY()) {
                break;
            }

            $e2 = 2 * $err;
            $x0 = 0;

            if ($e2 > -$dy) {
                $err -= $dy;
                $x0 = $node->getX() + $sx;
            }

            $y0 = 0;
            if ($e2 < $dx) {
                $err += $dx;
                $y0 = $node->getY() + $sy;
            }

            $node = $this->grid->getNode($x0, $y0);

            if ($node === null) {
                break;
            }
        }

        return $line;
    }
}
