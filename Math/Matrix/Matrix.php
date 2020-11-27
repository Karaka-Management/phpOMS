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

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Matrix class
 *
 * @package phpOMS\Math\Matrix
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @phpstan-implements \ArrayAccess<string, mixed>
 * @phpstan-implements \Iterator<string, mixed>
 */
class Matrix implements \ArrayAccess, \Iterator
{
    /**
     * Matrix.
     *
     * @var array<int, array<int, int|float>>
     * @since 1.0.0
     */
    protected array $matrix = [];

    /**
     * Columns.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $n = 0;

    /**
     * Rows.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $m = 0;

    /**
     * Iterator position.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $position = 0;

    /**
     * Constructor.
     *
     * @param int $m Rows
     * @param int $n Columns
     *
     * @since 1.0.0
     */
    public function __construct(int $m = 1, int $n = 1)
    {
        $this->n = $n;
        $this->m = $m;

        for ($i = 0; $i < $m; ++$i) {
            $this->matrix[$i] = \array_fill(0, $n, 0);
        }
    }

    /**
     * Create matrix from array
     *
     * @param array $matrix Matrix array
     *
     * @return self
     *
     * @since 1.0.0
     */
    public static function fromArray(array $matrix) : self
    {
        $m = new self();
        $m->setMatrix($matrix);

        return $m;
    }

    /**
     * Set value.
     *
     * @param int       $m     Row
     * @param int       $n     Column
     * @param int|float $value Value
     *
     * @return void
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
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
     * @return int|float
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    public function get(int $m, int $n = 0)
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
     * @since 1.0.0
     */
    public function transpose() : self
    {
        $matrix = new self($this->n, $this->m);
        $matrix->setMatrix(\array_map(null, ...$this->matrix));

        return $matrix;
    }

    /**
     * Get matrix array.
     *
     * @return array<int, array<int, mixed>>
     *
     * @since 1.0.0
     */
    public function getMatrix() : array
    {
        return $this->matrix;
    }

    /**
     * Get sub matrix array.
     *
     * @param int $iRow Start row
     * @param int $lRow End row
     * @param int $iCol Start col
     * @param int $lCol End col
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function getSubMatrix(int $iRow, int $lRow, int $iCol, int $lCol) : self
    {
        $X = [[]];
        for ($i = $iRow; $i <= $lRow; ++$i) {
            for ($j = $iCol; $j <= $lCol; ++$j) {
                $X[$i - $iRow][$j - $iCol] = $this->matrix[$i][$j];
            }
        }

        $matrix = new self();
        $matrix->setMatrix($X);

        return $matrix;
    }

    /**
     * Get sub matrix array.
     *
     * @param int[] $rows Row indices
     * @param int[] $cols Row indices
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function getSubMatrixByColumnsRows(array $rows, array $cols) : self
    {
        $X       = [[]];
        $rlength = \count($rows);
        $clength = \count($cols);

        for ($i = 0; $i < $rlength; ++$i) {
            for ($j = 0; $j < $clength; ++$j) {
                $X[$i][$j] = $this->matrix[$rows[$i]][$cols[$j]];
            }
        }

        $matrix = new self();
        $matrix->setMatrix($X);

        return $matrix;
    }

    /**
     * Get sub matrix array.
     *
     * @param int   $iRow Start row
     * @param int   $lRow End row
     * @param int[] $cols Row indices
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function getSubMatrixByColumns(int $iRow, int $lRow, array $cols) : self
    {
        $X      = [[]];
        $length = \count($cols);

        for ($i = $iRow; $i <= $lRow; ++$i) {
            for ($j = 0; $j < $length; ++$j) {
                $X[$i - $iRow][$j] = $this->matrix[$i][$cols[$j]];
            }
        }

        $matrix = new self();
        $matrix->setMatrix($X);

        return $matrix;
    }

    /**
     * Get sub matrix array.
     *
     * @param int[] $rows Row indices
     * @param int   $iCol Start col
     * @param int   $lCol End col
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function getSubMatrixByRows(array $rows, int $iCol, int $lCol) : self
    {
        $X      = [[]];
        $length = \count($rows);

        for ($i = 0; $i < $length; ++$i) {
            for ($j = $iCol; $j <= $lCol; ++$j) {
                $X[$i][$j - $iCol] = $this->matrix[$rows[$i]][$j];
            }
        }

        $matrix = new self();
        $matrix->setMatrix($X);

        return $matrix;
    }

    /**
     * Get matrix array.
     *
     * @return array<int, array<int, int|float>>
     *
     * @since 1.0.0
     */
    public function toArray() : array
    {
        return $this->matrix;
    }

    /**
     * Is symmetric.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isSymmetric() : bool
    {
        $isSymmetric = true;
        for ($j = 0; ($j < $this->m) & $isSymmetric; ++$j) {
            for ($i = 0; ($i < $this->n) & $isSymmetric; ++$i) {
                $isSymmetric = ($this->matrix[$i][$j] === $this->matrix[$j][$i]);
            }
        }

        return $isSymmetric;
    }

    /**
     * Get matrix rank.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function rank() : int
    {
        $matrix = $this->matrix;
        $mDim   = $this->m;
        $nDim   = $this->n;

        $rank     = 0;
        $selected = \array_fill(0, $mDim, false);

        for ($i = 0; $i < $nDim; ++$i) {
            for ($j = 0; $j < $mDim; ++$j) {
                if (!$selected[$j] && \abs($matrix[$j][$i]) > 0.0001) {
                    break;
                }
            }

            if ($j === $mDim) {
                continue;
            }

            ++$rank;
            $selected[$j] = true;
            for ($p = $i + 1; $p < $nDim; ++$p) {
                $matrix[$j][$p] /= $matrix[$j][$i];
            }

            for ($k = 0; $k < $mDim; ++$k) {
                if ($k !== $j && \abs($matrix[$k][$i]) > 0.0001) {
                    for ($p = $i + 1; $p < $nDim; ++$p) {
                        $matrix[$k][$p] -= $matrix[$j][$p] * $matrix[$k][$i];
                    }
                }
            }
        }

        return $rank;
    }

    /**
     * Set matrix array.
     *
     * @param array<int, array<int|float>> $matrix Matrix
     *
     * @return Matrix
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    public function setMatrix(array $matrix) : self
    {
        $this->m      = \count($matrix);
        $this->n      = !\is_array($matrix[0] ?? 1) ? 1 : \count($matrix[0]);
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
     * @since 1.0.0
     */
    public function sub($value) : self
    {
        if ($value instanceof self) {
            return $this->add($value->mult(-1));
        } elseif (!\is_string($value) && \is_numeric($value)) {
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
     * @since 1.0.0
     */
    public function add($value) : self
    {
        if ($value instanceof self) {
            return $this->addMatrix($value);
        } elseif (!\is_string($value) && \is_numeric($value)) {
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
     * @since 1.0.0
     */
    private function addMatrix(self $matrix) : self
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

        $newMatrix = new self($this->m, $this->n);
        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    /**
     * Get matrix rows.
     *
     * @return int
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    private function addScalar($scalar) : self
    {
        $newMatrixArr = $this->matrix;

        foreach ($newMatrixArr as $i => $vector) {
            foreach ($vector as $j => $value) {
                $newMatrixArr[$i][$j] += $scalar;
            }
        }

        $newMatrix = new self($this->m, $this->n);
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
     * @since 1.0.0
     */
    public function mult($value) : self
    {
        if ($value instanceof self) {
            return $this->multMatrix($value);
        } elseif (!\is_string($value) && \is_numeric($value)) {
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
     * @since 1.0.0
     */
    private function multMatrix(self $matrix) : self
    {
        $nDim = $matrix->getN();
        $mDim = $matrix->getM();

        if ($mDim !== $this->n) {
            throw new InvalidDimensionException($mDim . 'x' . $nDim);
        }

        $matrixArr    = $matrix->getMatrix();
        $newMatrix    = new self($this->m, $nDim);
        $newMatrixArr = $newMatrix->getMatrix();

        for ($i = 0; $i < $this->m; ++$i) { // Row of $this
            for ($c = 0; $c < $nDim; ++$c) { // Column of $matrix
                $temp = 0;

                for ($j = 0; $j < $mDim; ++$j) { // Row of $matrix
                    $temp += ($this->matrix[$i][$j] ?? 0) * ($matrixArr[$j][$c] ?? 0);
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
     * @since 1.0.0
     */
    private function multScalar($scalar) : self
    {
        $newMatrixArr = $this->matrix;

        foreach ($newMatrixArr as $i => $vector) {
            foreach ($vector as $j => $value) {
                $newMatrixArr[$i][$j] *= $scalar;
            }
        }

        $newMatrix = new self($this->m, $this->n);
        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    /**
     * Upper triangulize matrix.
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function upperTriangular() : self
    {
        $matrix = new self($this->n, $this->n);

        $matrixArr = $this->matrix;
        $this->upperTrianglize($matrixArr);
        $matrix->setMatrix($matrixArr);

        return $matrix;
    }

    /**
     * Trianglize matrix.
     *
     * @param array<int, array<int|float>> $arr Matrix to trianglize
     *
     * @return int Det sign
     *
     * @since 1.0.0
     */
    private function upperTrianglize(array &$arr) : int
    {
        $n    = $this->n;
        $sign = 1;

        for ($i = 0; $i < $n; ++$i) {
            $max = 0;

            for ($j = $i; $j < $n; ++$j) {
                if (\abs($arr[$j][$i]) > \abs($arr[$max][$i])) {
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
     * Inverse matrix.
     *
     * @return Matrix
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    public function inverse() : self
    {
        return $this->solve(new IdentityMatrix($this->m));
    }

    /**
     * Solve matrix
     *
     * @param Matrix $B Matrix/Vector b
     *
     * @return Matrix|Vector
     *
     * @since 1.0.0
     */
    public function solve(self $B) : self
    {
        $M = $this->m === $this->n ? new LUDecomposition($this) : new QRDecomposition($this);

        return $M->solve($B);
    }

    /**
     * Calculate det.
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function det() : float
    {
        $L = new LUDecomposition($this);
        return $L->det();
    }

    /**
     * {@inheritdoc}
     */
    public function current() : int
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
    public function next() : void
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
    public function valid() : bool
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
    public function rewind() : void
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value) : void
    {
        $row                                           = (int) ($offset / $this->m);
        $this->matrix[$row][$offset - $row * $this->n] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset) : void
    {
        $row = (int) ($offset / $this->m);
        unset($this->matrix[$row][$offset - $row * $this->n]);
    }
}
