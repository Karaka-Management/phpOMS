<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Matrix
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Matrix;

/**
 * Vector class
 *
 * @package phpOMS\Math\Matrix
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Vector extends Matrix
{
    /**
     * Create vector from array
     *
     * @param array $vector Matrix array
     *
     * @return self
     *
     * @since 1.0.0
     */
    public static function fromArray(array $vector) : self
    {
        $v = new self(\count($vector), 1);
        $v->setMatrixV($vector);

        return $v;
    }

    /**
     * Set vector value
     *
     * @param int       $m     Position to set
     * @param int|float $value Value to set
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setV(int $m, int | float $value) : void
    {
        parent::set($m , 0, $value);
    }

    /**
     * Get vector value
     *
     * @param int $m Position to get
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    public function getV(int $m) : int | float
    {
        return parent::get($m, 0);
    }

    /**
     * Set matrix
     *
     * @param array<int, int|float> $vector 1-Dimensional array
     *
     * @return Vector
     *
     * @since 1.0.0
     */
    public function setMatrixV(array $vector) : self
    {
        foreach ($vector as $key => $value) {
            $this->setV($key, $value);
        }

        return $this;
    }

    /**
     * Calculate the cross product
     *
     * @param self $vector 3 Vector
     *
     * @return Vector
     *
     * @since 1.0.0
     */
    public function cross3(self $vector) : self
    {
        $crossArray = [
            $this->getV(1) * $vector->getV(2) - $this->getV(2) * $vector->getV(1),
            $this->getV(2) * $vector->getV(0) - $this->getV(0) * $vector->getV(2),
            $this->getV(0) * $vector->getV(1) - $this->getV(1) * $vector->getV(0),
        ];

        return self::fromArray($crossArray);
    }
}
