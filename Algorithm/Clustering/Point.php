<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Algorithm\Clustering
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Clustering;

/**
 * Point for clustering
 *
 * @package phpOMS\Algorithm\Clustering
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Point implements PointInterface
{
    /**
     * Coordinates of the point
     *
     * @var array<int, int|float>
     * @since 1.0.0
     */
    public array $coordinates = [];

    /**
     * Group or cluster this point belongs to
     *
     * @var int
     * @since 1.0.0
     */
    public int $group = 0;

    /**
     * Name of the point
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Constructor.
     *
     * @param array<int, int|float> $coordinates Coordinates of the point
     * @param string                $name        Name of the point
     *
     * @since 1.0.0
     */
    public function __construct(array $coordinates, string $name = '')
    {
        $this->coordinates = $coordinates;
        $this->name        = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getCoordinates() : array
    {
        return $this->coordinates;
    }

    /**
     * {@inheritdoc}
     */
    public function getCoordinate(int $index) : int | float
    {
        return $this->coordinates[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function setCoordinate(int $index, int | float $value) : void
    {
        $this->coordinates[$index] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function isEquals(PointInterface $point) : bool
    {
        return $this->name === $point->name && $this->coordinates === $point->coordinates;
    }
}
