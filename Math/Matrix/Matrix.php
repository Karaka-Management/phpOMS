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

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Matrix class
 *
 * @package    phpOMS\Math\Matrix
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Matrix implements \ArrayAccess, \Iterator
{
    /**
     * Matrix.
     *
     * @var array
     * @since 1.0.0
     */
    protected $matrix = [];

    /**
     * Columns.
     *
     * @var int
     * @since 1.0.0
     */
    protected $n = 0;

    /**
     * Rows.
     *
     * @var int
     * @since 1.0.0
     */
    protected $m = 0;

    /**
     * Iterator position.
     *
     * @var int
     * @since 1.0.0
     */
    protected $position = 0;

    /**
     * Constructor.
     *
     * @param int $m Rows
     * @param int $n Columns
     *
     * @since  1.0.0
     */
    public function __construct(int $m = 1, int $n = 1)
    {
        $this->n = $n;
        $this->m = $m;

        for ($i = 0; $i < $m; ++$i) {
            $this->matrix[$i] = array_fill(0, $n, 0);
        }
    }

    /**
     * Set value.
     *
     * @param int $m     Row
     * @param int $n     Column
     * @param int $value Value
     * 
     * @return void
     *
     * @throws InvalidDimensionException
     *
     * @since  1.0.0
     */
    public function set(int $m, int $n, $value) : void
    {
        if (!isset($this->matrix[$m], $this->matrix[$m][$n])) {
            throw new InvalidDimensionException($m . 'x' . $n);
        }

        $this->matrix[$m][$n] = $value;
    }

    /**
     * Get value.
     *
     * @param int $m Row
     * @param int $n Column
     *
     * @return mixed
     *
     * @throws InvalidDimensionException
     *
     * @since  1.0.0
     */
    public function get(int $m, int $n)
    {
        if (!isset($this->matrix[$m], $this->matrix[$m][$n])) {
            throw new InvalidDimensionException($m . 'x' . $n);
        }

        return $this->matrix[$m][$n];
    }

    /**
     * Transpose matrix.
     *
     * @return Matrix
     *
     * @since  1.0.0
     */
    public function transpose() : Matrix
    {
        $matrix = new Matrix($this->n, $this->m);
        $matrix->setMatrix(array_map(null, ...$this->matrix));

        return $matrix;
    }

    /**
     * Get matrix array.
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getMatrix() : array
    {
        return $this->matrix;
    }

    /**
     * Get matrix array.
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function toArray() : array
    {
        return $this->matrix;
    }

    /**
     * Get matrix rank.
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function rank() : int
    {
        $matrix = $this->matrix;
        $mDim   = $this->m;
        $nDim   = $this->n;

        if ($this->m > $this->n) {
            $mDim   = $this->n;
            $nDim   = $this->m;
            $matrix = array_map(null, ...$matrix);
        }

        $rank = $mDim;

        for ($row = 0; $row < $rank; ++$row) {
            if (isset($matrix[$row][$row]) && $matrix[$row][$row] !== 0) {
                for ($col = 0; $col < $mDim; ++$col) {
                    if ($col !== $row) {
                        $mult = $matrix[$col][$row] / $matrix[$row][$row];

                        for ($i = 0; $i < $rank; ++$i) {
                            $matrix[$col][$i] -= $mult * $matrix[$row][$i];
                        }
                    }
                }
            } else {
                $reduce = true;

                for ($i = $row + 1; $i < $mDim; ++$i) {
                    if (isset($matrix[$i][$row]) && $matrix[$i][$row] !== 0) {
                        $this->swapRow($matrix, $row, $i, $rank);
                        $reduce = false;
                        break;
                    }
                }

                if ($reduce) {
                    --$rank;

                    for ($i = 0; $i < $mDim; ++$i) {
                        $matrix[$i][$row] = $matrix[$i][$rank];
                    }

                    --$row;
                }
            }
        }

        return $rank;
    }

    /**
     * Swap values in rows
     * 
     * @param array $matrix Matrix reference to modify
     * @param int   $row1   Row to swap
     * @param int   $row2   Row to swap
     * @param int   $col    Max col to swap to
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function swapRow(array &$matrix, int $row1, int $row2, int $col) : void
    {
        for ($i = 0; $i < $col; ++$i) {
            $temp          = $matrix[$row1][$i];
            $matrix[$row1] = $matrix[$row2][$i];
            $matrix[$row2] = $temp;
        }
    }

    /**
     * Set matrix array.
     *
     * @param array $matrix Matrix
     *
     * @return Matrix
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public function setMatrix(array $matrix) : Matrix
    {
        $this->m      = count($matrix);
        $this->n      = count($matrix[0] ?? 1);
        $this->matrix = $matrix;

        return $this;
    }

    /**
     * Subtract right.
     *
     * @param mixed $value Value
     *
     * @return Matrix
     *
     * @throws \InvalidArgumentException
     *
     * @since  1.0.0
     */
    public function sub($value) : Matrix
    {
        if ($value instanceof Matrix) {
            return $this->add($value->mult(-1));
        } elseif (is_numeric($value)) {
            return $this->add(-$value);
        }

        throw new \InvalidArgumentException('Type');
    }

    /**
     * Add right.
     *
     * @param mixed $value Value
     *
     * @return Matrix
     *
     * @throws \InvalidArgumentException
     *
     * @since  1.0.0
     */
    public function add($value) : Matrix
    {
        if ($value instanceof Matrix) {
            return $this->addMatrix($value);
        } elseif (is_numeric($value)) {
            return $this->addScalar($value);
        }

        throw new \InvalidArgumentException();
    }

    /**
     * Add matrix.
     *
     * @param Matrix $matrix Matrix to add
     *
     * @return Matrix
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    private function addMatrix(Matrix $matrix) : Matrix
    {
        if ($this->m !== $matrix->getM() || $this->n !== $matrix->getN()) {
            throw new InvalidDimensionException($matrix->getM() . 'x' . $matrix->getN());
        }

        $matrixArr    = $matrix->getMatrix();
        $newMatrixArr = $this->matrix;

        foreach ($newMatrixArr as $i => $vector) {
            foreach ($vector as $j => $value) {
                $newMatrixArr[$i][$j] += $matrixArr[$i][$j];
            }
        }

        $newMatrix = new Matrix($this->m, $this->n);
        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    /**
     * Get matrix rows.
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function getM() : int
    {
        return $this->m;
    }

    /**
     * Get matrix columns.
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function getN() : int
    {
        return $this->n;
    }

    /**
     * Add scalar.
     *
     * @param mixed $scalar Scalar
     *
     * @return Matrix
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    private function addScalar($scalar) : Matrix
    {
        $newMatrixArr = $this->matrix;

        foreach ($newMatrixArr as $i => $vector) {
            foreach ($vector as $j => $value) {
                $newMatrixArr[$i][$j] += $scalar;
            }
        }

        $newMatrix = new Matrix($this->m, $this->n);
        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    /**
     * Multiply right.
     *
     * @param mixed $value Factor
     *
     * @return Matrix
     *
     * @throws \InvalidArgumentException
     *
     * @since  1.0.0
     */
    public function mult($value) : Matrix
    {
        if ($value instanceof Matrix) {
            return $this->multMatrix($value);
        } elseif (is_numeric($value)) {
            return $this->multScalar($value);
        }

        throw new \InvalidArgumentException('Type');
    }

    /**
     * Multiply matrix.
     *
     * @param Matrix $matrix Matrix to multiply with
     *
     * @return Matrix
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    private function multMatrix(Matrix $matrix) : Matrix
    {
        $nDim = $matrix->getN();
        $mDim = $matrix->getM();

        if ($this->n !== $mDim) {
            throw new InvalidDimensionException($mDim . 'x' . $nDim);
        }

        $matrixArr    = $matrix->getMatrix();
        $newMatrix    = new Matrix($this->m, $nDim);
        $newMatrixArr = $newMatrix->getMatrix();

        for ($i = 0; $i < $this->m; ++$i) { // Row of $this
            for ($c = 0; $c < $nDim; ++$c) { // Column of $matrix
                $temp = 0;

                for ($j = 0; $j < $mDim; ++$j) { // Row of $matrix
                    $temp += $this->matrix[$i][$j] * $matrixArr[$j][$c];
                }

                $newMatrixArr[$i][$c] = $temp;
            }
        }

        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    /**
     * Multiply matrix.
     *
     * @param mixed $scalar Scalar value
     *
     * @return Matrix
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    private function multScalar($scalar) : Matrix
    {
        $newMatrixArr = $this->matrix;

        foreach ($newMatrixArr as $i => $vector) {
            foreach ($vector as $j => $value) {
                $newMatrixArr[$i][$j] *= $scalar;
            }
        }

        $newMatrix = new Matrix($this->m, $this->n);
        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    /**
     * Upper triangulize matrix.
     *
     * @return Matrix
     *
     * @since  1.0.0
     */
    public function upperTriangular() : Matrix
    {
        $matrix = new Matrix($this->n, $this->n);

        $matrixArr = $this->matrix;
        $this->upperTrianglize($matrixArr);
        $matrix->setMatrix($matrixArr);

        return $matrix;
    }

    /**
     * Trianglize matrix.
     *
     * @param array $arr Matrix to trianglize
     *
     * @return int Det sign
     *
     * @since  1.0.0
     */
    private function upperTrianglize(array &$arr) : int
    {
        $n    = $this->n;
        $sign = 1;

        for ($i = 0; $i < $n; ++$i) {
            $max = 0;

            for ($j = $i; $j < $n; ++$j) {
                if (abs($arr[$j][$i]) > abs($arr[$max][$i])) {
                    $max = $j;
                }
            }

            if ($max) {
                $sign      = -$sign;
                $temp      = $arr[$i];
                $arr[$i]   = $arr[$max];
                $arr[$max] = $temp;
            }

            if (!$arr[$i][$i]) {
                return 0;
            }

            for ($j = $i + 1; $j < $n; ++$j) {
                $r = $arr[$j][$i] / $arr[$i][$i];

                if (!$r) {
                    continue;
                }

                for ($c = $i; $c < $n; ++$c) {
                    $arr[$j][$c] -= $arr[$i][$c] * $r;
                }
            }
        }

        return $sign;
    }

    /**
     * Lower triangulize matrix.
     *
     * @return Matrix
     *
     * @since  1.0.0
     */
    public function lowerTriangular() : Matrix
    {
        // todo: implement
        return new Matrix($this->m, $this->n);
    }

    /**
     * Inverse matrix.
     *
     * @param int $algorithm Algorithm for inversion
     *
     * @return Matrix
     *
     * @throws InvalidDimensionException
     *
     * @since  1.0.0
     */
    public function inverse(int $algorithm = InverseType::GAUSS_JORDAN) : Matrix
    {
        return $this->solve(new IdentityMatrix($this->m));
    }

    /**
     * Solve matrix
     * 
     * @param Matix $B Matrix/Vector b
     *
     * @return Matrix
     *
     * @since  1.0.0
     */
    public function solve(Matrix $B) : Matrix
    {
        $M = $this->m === $this->n ? new LUDecomposition($this) : new QRDecomposition($this);

        return $M->solve($B);
    }

    /**
     * Calculate det.
     *
     * @return float
     *
     * @since  1.0.0
     */
    public function det() : float
    {
        $L = new LUDecomposition($this);
        return $L->det();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->offsetGet($this->position);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $row = (int) ($offset / $this->m);

        return $this->matrix[$row][$offset - $row * $this->n];
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->offsetExists($this->position);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        $row = (int) ($offset / $this->m);

        return isset($this->matrix[$row][$offset - $row * $this->n]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $row                                           = (int) ($offset / $this->m);
        $this->matrix[$row][$offset - $row * $this->n] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $row = (int) ($offset / $this->m);
        unset($this->matrix[$row][$offset - $row * $this->n]);
    }
}
