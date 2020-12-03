<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Math\Matrix
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Matrix;

/**
 * Vector class
 *
 * @package phpOMS\Math\Matrix
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
     * @param int   $m     Position to set
     * @param mixed $value Value to set
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setV(int $m, $value) : void
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
    public function getV(int $m) : int|float
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
}
