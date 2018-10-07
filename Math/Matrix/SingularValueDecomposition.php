<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Math\Matrix
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Math\Matrix;

/**
 * Singular value decomposition
 *
 * @package    phpOMS\Math\Matrix
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class SingularValueDecomposition
{
    /**
     * U matrix.
     *
     * @var array[]
     * @since 1.0.0
     */
    private $U = [];

    /**
     * V matrix.
     *
     * @var array[]
     * @since 1.0.0
     */
    private $V = [];

    /**
     * Singular values.
     *
     * @var array
     * @since 1.0.0
     */
    private $S = [];

    /**
     * Dimension m
     *
     * @var int
     * @since 1.0.0
     */
    private $m = 0;

    /**
     * Dimension n
     *
     * @var int
     * @since 1.0.0
     */
    private $n = 0;

    public function getU() : Matrix
    {
        $matrix = new Matrix();
        $matrix->setMatrix($this->U);

        return $matrix;
    }

    public function getV() : Matrix
    {
        $matrix = new Matrix();
        $matrix->setMatrix($this->V);

        return $matrix;
    }

    public function getS() : Matrix
    {
        $S = [[]];
        for ($i = 0; $i < $this->n; ++$i) {
            for ($j = 0; $j < $this->n; ++$j) {
                $S[$i][$j] = 0.0;
            }
            $S[$i][$i] = $this->s[$i];
        }
        
        $matrix = new Matrix();
        $matrix->setMatrix($this->V);

        return $matrix;
    }

    public function getSingularValues() : Vector
    {
        $vector = new Vector();
        $vector->setMatrix($this->S);

        return $vector;
    }

    public function norm2() : float
    {
        return $this->S[0];
    }

    public function cond() : float
    {
        return $this->S[0] / $this->S[\min($this->m, $this->n) - 1];
    }

    public function rank() : int
    {
        $eps = 0.00001;
        $tol = \max($this->m, $this->n) * $this->S[0] * $eps;
        $r   = 0;

        $length = \count($this->S);
        for ($i = 0; $i < $length; ++$i) {
            if ($this->S[$i] > $tol) {
                ++$r;
            }
        }

        return $r;
    }
}
