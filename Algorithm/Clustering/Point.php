<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\Clustering
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Clustering;

/**
 * Point for clustering
 *
 * @package phpOMS\Algorithm\Clustering
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Point implements PointInterface
{
    /**
     * Coordinates of the point
     *
     * @var   array<int, int|float>
     * @sicne 1.0.0
     */
    private array $coordinates = [];

    /**
     * Group or cluster this point belongs to
     *
     * @var   int
     * @since 1.0.0
     */
    private int $group = 0;

    /**
     * Name of the point
     *
     * @var   string
     * @since 1.0.0
     */
    private string $name = '';

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
    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    /**
     * {@inheritdoc}
     */
    public function getCoordinate($index)
    {
        return $this->coordinates[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function setCoordinate($index, $value) : void
    {
        $this->coordinates[$index] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroup() : int
    {
        return $this->group;
    }

    /**
     * {@inheritdoc}
     */
    public function setGroup(int $group) : void
    {
        $this->group = $group;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return $this->name;
    }
}
