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
     * Angle between two vectors
     *
     * @param self $v Vector
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function cosine(self $v) : float
    {
        $dotProduct = 0.0;
        for ($i = 0; $i < $this->m; ++$i) {
            $dotProduct += $this->matrix[$i][0] * $v->matrix[$i][0];
        }

        $sumOfSquares = 0;
        foreach ($this->matrix as $value) {
            $sumOfSquares += $value[0] * $value[0];
        }
        $magnitude1 = \sqrt($sumOfSquares);

        $sumOfSquares = 0.0;
        foreach ($v->matrix as $value) {
            $sumOfSquares += $value[0] * $value[0];
        }
        $magnitude2 = \sqrt($sumOfSquares);

        if ($magnitude1 === 0.0 || $magnitude2 === 0.0) {
            return \PHP_FLOAT_MAX;
        }

        return $dotProduct / ($magnitude1 * $magnitude2);
    }


    /**
     * Calculate the eucledian dot product
     *
     * @param selft $vector Vector
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function dot(self $vector) : float
    {
        $length = $this->m;
        $m1     = 0;
        $m2     = 0;
        $prod   = 0;

        for ($i = 0; $i < $length; ++$i) {
            $m1   += $this->matrix[$i][0] * $this->matrix[$i][0];
            $m2   += $vector->matrix[$i][0] * $vector->matrix[$i][0];
            $prod += $this->matrix[$i][0] * $vector->matrix[$i][0];
        }

        $m1 = \sqrt($m1);
        $m2 = \sqrt($m2);

        $cos = $prod / ($m1 * $m2);

        return $m1 * $m2 * $cos;
    }

    /**
     * Calculate the angle between two vectors
     *
     * @param self $vector Vector
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function angle(self $vector) : float
    {
        $length = $this->m;
        $m1     = 0;
        $m2     = 0;
        $prod   = 0;

        for ($i = 0; $i < $length; ++$i) {
            $m1   += $this->matrix[$i][0] * $this->matrix[$i][0];
            $m2   += $vector->matrix[$i][0] * $vector->matrix[$i][0];
            $prod += $this->matrix[$i][0] * $vector->matrix[$i][0];
        }

        $m1 = \sqrt($m1);
        $m2 = \sqrt($m2);

        return \acos($prod / ($m1 * $m2));
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
