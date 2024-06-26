<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Algorithm\PathFinding
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\PathFinding;

/**
 * Node on grid.
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class AStarNode extends Node
{
    /**
     * The g score is cost of the path
     *
     * @var float
     * @since 1.0.0
     */
    private float $g = 0.0;

    /**
     * The heuristic distance is the cost to the end node
     *
     * @var float
     * @since 1.0.0
     */
    private ?float $h = null;

    /**
     * The f score is defined as f(n) = g(n) + h(n)
     *
     * @var float
     * @since 1.0.0
     */
    private float $f = 0.0;

    /**
     * Define as checked node
     *
     * @var bool
     * @since 1.0.0
     */
    private bool $isClosed = false;

    /**
     * Define as potential candidate
     *
     * @var bool
     * @since 1.0.0
     */
    private bool $isOpened = false;

    /**
     * Is checked?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isClosed() : bool
    {
        return $this->isClosed;
    }

    /**
     * Is potential candidate
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isOpened() : bool
    {
        return $this->isOpened;
    }

    /**
     * Set check status
     *
     * @param bool $isClosed Is closed
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setClosed(bool $isClosed) : void
    {
        $this->isClosed = $isClosed;
    }

    /**
     * Set potential candidate
     *
     * @param bool $isOpened Is potential candidate
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setOpened(bool $isOpened) : void
    {
        $this->isOpened = $isOpened;
    }

    /**
     * Set the g score
     *
     * @param float $g G score
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setG(float $g) : void
    {
        $this->g = $g;
    }

    /**
     * Set the heuristic distance
     *
     * @param float $h H distance
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setH(?float $h) : void
    {
        $this->h = $h;
    }

    /**
     * Set the f score
     *
     * @param float $f F score
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setF(float $f) : void
    {
        $this->f = $f;
    }

    /**
     * Get the g score
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getG() : float
    {
        return $this->g;
    }

    /**
     * Get the heuristic distance
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getH() : ?float
    {
        return $this->h;
    }

    /**
     * Get the f score
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getF() : float
    {
        return $this->f;
    }
}
