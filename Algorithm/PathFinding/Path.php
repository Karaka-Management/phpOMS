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
    /**
     * Nodes in the path
     *
     * @var Node[]
     * @since 1.0.0
     */
    public array $nodes = [];

    /**
     * Grid this path belongs to
     *
     * @var Grid
     * @since 1.0.0
     */
    private Grid $grid;

    /**
     * Nodes in the path
     *
     * @var Node[]
     * @since 1.0.0
     */
    private array $expandedNodes = [];

    /**
     * Path length
     *
     * @var float
     * @since 1.0.0
     */
    private float $length = 0.0;

    /**
     * Cosntructor.
     *
     * @param Grid $grid Grid this path belongs to
     *
     * @since 1.0.0
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Add node to the path
     *
     * @param Node $node Node
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addNode(Node $node) : void
    {
        $this->nodes[] = $node;
    }

    /**
     * Get the path length (euclidean)
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getLength() : float
    {
        $n = \count($this->nodes);

        $dist = 0.0;

        for ($i = 1; $i < $n; ++$i) {
            $dx = $this->nodes[$i - 1]->getX() - $this->nodes[$i]->getX();
            $dy = $this->nodes[$i - 1]->getY() - $this->nodes[$i]->getY();

            $dist += \sqrt($dx * $dx + $dy * $dy);
        }

        return $dist;
    }

    /**
     * Get the incomplete node path
     *
     * @return Node[]
     *
     * @since 1.0.0
     */
    public function getPath() : array
    {
        return $this->nodes;
    }

    /**
     * Get the complete node path
     *
     * The path may only contain the jump points or pivot points.
     * In order to get every node it needs to be expanded.
     *
     * @return Node[]
     *
     * @since 1.0.0
     */
    public function expandPath() : array
    {
        if (empty($this->expandedNodes)) {
            //$reverse = \array_reverse($this->nodes);
            $reverse = $this->nodes;
            $length  = \count($reverse);

            if ($length < 2) {
                return $reverse;
            }

            $expanded = [];
            for ($i = 0; $i < $length - 1; ++$i) {
                $coord0 = $reverse[$i];
                $coord1 = $reverse[$i + 1];

                $interpolated = $this->interpolate($coord0, $coord1);
                $expanded     = \array_merge($expanded, $interpolated);
            }

            $expanded[]          = $reverse[$length - 1];
            $this->expandedNodes = $expanded;
        }

        return $this->expandedNodes;
    }

    /**
     * Find nodes in bettween two nodes.
     *
     * The path may only contain the jump points or pivot points.
     * In order to get every node it needs to be expanded.
     *
     * @param Node $node1 Node
     * @param Node $node2 Node
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function interpolate(Node $node1, Node $node2) : array
    {
        $dx = \abs($node2->getX() - $node1->getX());
        $dy = \abs($node2->getY() - $node1->getY());

        $sx = ($node1->getX() < $node2->getX()) ? 1 : -1;
        $sy = ($node1->getY() < $node2->getY()) ? 1 : -1;

        $node = $node1;
        $err  = $dx - $dy;

        $x0 = $node->getX();
        $y0 = $node->getY();

        $line = [];
        while (true) {
            if ($node->getX() === $node2->getX() && $node->getY() === $node2->getY()) {
                break;
            }

            $line[] = $node;

            $e2 = 2 * $err;

            if ($e2 > -$dy) {
                $err -= $dy;
                $x0   = $x0 + $sx;
            }

            if ($e2 < $dx) {
                $err += $dx;
                $y0   = $y0 + $sy;
            }

            $node = $this->grid->getNode($x0, $y0);

            if ($node === null) {
                break;
            }
        }

        return $line;
    }
}
