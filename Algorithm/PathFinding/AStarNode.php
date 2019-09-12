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
 * Node on grid.
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class AStarNode extends Node
{
    private float $g = 0.0;
    private ?float $h = null;
    private float $f = 0.0;

    private bool $isClosed = false;
    private bool $isOpened = false;
    private bool $isTested = false;

    public function isClosed() : bool
    {
        return $this->isClosed;
    }

    public function isOpened() : bool
    {
        return $this->isOpened;
    }

    public function isTested() : bool
    {
        return $this->isTested;
    }

    public function setClosed(bool $isClosed) : void
    {
        $this->isClosed = $isClosed;
    }

    public function setOpened(bool $isOpened) : void
    {
        $this->isOpened = $isOpened;
    }

    public function setTested(bool $isTested) : void
    {
        $this->isTested = $isTested;
    }

    public function setG(float $g) : void
    {
        $this->g = $g;
    }

    public function setH(?float $h) : void
    {
        $this->h = $h;
    }

    public function setF(float $f) : void
    {
        $this->f = $f;
    }

    public function getG() : float
    {
        return $this->g;
    }

    public function getH() : ?float
    {
        return $this->h;
    }

    public function getF() : float
    {
        return $this->f;
    }
}