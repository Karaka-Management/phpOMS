<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Matrix
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Matrix;

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Matrix class
 *
 * @package phpOMS\Math\Matrix
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @phpstan-implements \ArrayAccess<int, int|float>
 * @phpstan-implements \Iterator<int, int|float>
 */
class Matrix implements \ArrayAccess, \Iterator
{
    /**
     * Epsilon for float comparison.
     *
     * @var float
     * @since 1.0.0
     */
    public const EPSILON = 4.88e-04;

    /**
     * Matrix.
     *
     * @var array<int, array<int, int|float>>
     * @since 1.0.0
     */
    public array $matrix = [];

    /**
     * Columns.
     *
     * @var int<0, max>
     * @since 1.0.0
     */
    protected int $n = 0;

    /**
     * Rows.
     *
     * @var int<0, max>
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
     * @param int<0, max> $m Rows
     * @param int<0, max> $n Columns
     *
     * @since 1.0.0
     */
    public function __construct(int $m = 1, int $n = 1)
    {
        $this->n = $n;
        $this->m = $m;

        $this->matrix = \array_fill(0, $m, \array_fill(0, $n, 0));
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
    public function set(int $m, int $n, int | float $value) : void
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
    public function get(int $m, int $n = 0) : int | float
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
     * Get matrix as 1D array.
     *
     * @return array<int, int|float>
     *
     * @since 1.0.0
     */
    public function toVectorArray() : array
    {
        $result = [];
        for ($i = 0; $i < $this->m; ++$i) {
            for ($j = 0; $j < $this->n; ++$j) {
                $result[] = $this->matrix[$i][$j];
            }
        }

        return $result;
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
                $isSymmetric = \abs($this->matrix[$i][$j] - $this->matrix[$j][$i]) < self::EPSILON;
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
                if (!$selected[$j] && \abs($matrix[$j][$i]) > self::EPSILON) {
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
                if ($k !== $j && \abs($matrix[$k][$i]) > self::EPSILON) {
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
     * @since 1.0.0
     */
    public function setMatrix(array $matrix) : self
    {
        $this->m      = \count($matrix);
        $this->n      = \is_array($matrix[0] ?? 1) ? \count($matrix[0]) : 1;
        $this->matrix = $matrix;

        return $this;
    }

    /**
     * Subtract right.
     *
     * @param int|float|self $value Value
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function sub(int | float | self $value) : self
    {
        if (\is_numeric($value)) {
            return $this->addScalar(-$value);
        }

        return $this->add($value->mult(-1));
    }

    /**
     * Add right.
     *
     * @param int|float|self $value Value
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function add(int | float | self $value) : self
    {
        if (\is_numeric($value)) {
            return $this->addScalar($value);
        }

        return $this->addMatrix($value);
    }

    /**
     * Add matrix.
     *
     * @param Matrix $matrix Matrix to add
     *
     * @return Matrix
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    private function addMatrix(self $matrix) : self
    {
        if ($this->m !== $matrix->getM() || $this->n !== $matrix->getN()) {
            throw new InvalidDimensionException($matrix->getM() . 'x' . $matrix->getN());
        }

        $matrixArr    = $matrix->toArray();
        $newMatrixArr = $this->matrix;

        foreach ($newMatrixArr as $i => $vector) {
            foreach ($vector as $j => $_) {
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
     * @return int<0, max>
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
     * @return int<0, max>
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
     * @param int|float $scalar Scalar
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    private function addScalar(int | float $scalar) : self
    {
        $newMatrixArr = $this->matrix;

        foreach ($newMatrixArr as $i => $vector) {
            foreach ($vector as $j => $_) {
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
     * @param int|float|self $value Factor
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function mult(int | float | self $value) : self
    {
        if (\is_numeric($value)) {
            return $this->multScalar($value);
        }

        return $this->multMatrix($value);
    }

    /**
     * Multiply matrix.
     *
     * @param Matrix $matrix Matrix to multiply with
     *
     * @return Matrix
     *
     * @throws InvalidDimensionException
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

        $matrixArr    = $matrix->toArray();
        $newMatrixArr = \array_fill(0, $this->m, \array_fill(0, $nDim, 0));

        if ($mDim > 10 || $nDim > 10) {
            // Standard transposed for iteration over rows -> higher cache hit
            $transposedMatrixArr = [];
            for ($k = 0; $k < $mDim; ++$k) {
                for ($j = 0; $j < $nDim; ++$j) {
                    $transposedMatrixArr[$k][$j] = $matrixArr[$j][$k];
                }
            }

            for ($i = 0; $i < $this->m; ++$i) {
                for ($j = 0; $j < $this->n; ++$j) {
                    $temp = 0;

                    for ($k = 0; $k < $mDim; ++$k) {
                        $temp += $this->matrix[$i][$k] * $transposedMatrixArr[$i][$k];
                    }

                    $newMatrixArr[$i][$j] = $temp;
                }
            }
        } else {
            // Standard
            for ($i = 0; $i < $this->m; ++$i) {
                for ($j = 0; $j < $nDim; ++$j) {
                    $temp = 0;

                    for ($k = 0; $k < $mDim; ++$k) {
                        $temp += $this->matrix[$i][$k] * $matrixArr[$k][$j];
                    }

                    $newMatrixArr[$i][$j] = $temp;
                }
            }
        }

        return self::fromArray($newMatrixArr);
    }

    /**
     * Multiply matrix.
     *
     * @param int|float $scalar Scalar value
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    private function multScalar(int | float $scalar) : self
    {
        $newMatrixArr = $this->matrix;

        foreach ($newMatrixArr as $i => $vector) {
            foreach ($vector as $j => $_) {
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

            if ($max !== 0) {
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
     * Sum the elements in the matrix.
     *
     * @param int $axis Axis (-1 -> all dimensions, 0 -> columns, 1 -> rows)
     *
     * @return int|float|self Returns int or float for axis -1
     *
     * @since 1.0.0
     */
    public function sum(int $axis = -1) : int|float|self
    {
        if ($axis === -1) {
            $sum = 0;

            foreach ($this->matrix as $row) {
                $sum += \array_sum($row);
            }

            return $sum;
        } elseif ($axis === 0) {
            $sum = [];
            foreach ($this->matrix as $row) {
                foreach ($row as $idx2 => $value) {
                    if (!isset($sum[$idx2])) {
                        $sum[$idx2] = 0;
                    }

                    $sum[$idx2] += $value;
                }
            }

            return Vector::fromArray($sum);
        } elseif ($axis === 1) {
            $sum = [];
            foreach ($this->matrix as $idx => $row) {
                $sum[$idx] = \array_sum($row);
            }

            return Vector::fromArray($sum);
        }

        return new self();
    }

    /**
     * Is matrix a diagonal matrix
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isDiagonal() : bool
    {
        if ($this->m !== $this->n) {
            return false;
        }

        for ($i = 0; $i < $this->m; ++$i) {
            for ($j = 0; $j < $this->n; ++$j) {
                if ($i !== $j && \abs($this->matrix[$i][$j]) > self::EPSILON) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Calculate the power of a matrix
     *
     * @param int|float $exponent Exponent
     *
     * @return self
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    public function pow(int | float $exponent) : self
    {
        if ($this->isDiagonal()) {
            $matrix = [];

            for ($i = 0; $i < $this->m; ++$i) {
                $row = [];
                for ($j = 0; $j < $this->m; ++$j) {
                    $row[] = $i === $j ? \pow($this->matrix[$i][$j], $exponent) : 0;
                }

                $matrix[] = $row;
            }

            return self::fromArray($matrix);
        } elseif (\is_int($exponent)) {
            if ($this->m !== $this->n) {
                throw new InvalidDimensionException($this->m . 'x' . $this->n);
            }

            $matrix = new IdentityMatrix($this->m);
            for ($i = 0; $i < $exponent; ++$i) {
                $matrix = $matrix->mult($this);
            }

            return $matrix;
        }

        $eig = new EigenvalueDecomposition($this);

        $d = $eig->getD();
        $m = $d->getM();

        for ($i = 0; $i < $m; ++$i) {
            $d->matrix[$i][$i] = \pow($d->matrix[$i][$i], $exponent);
        }

        return $eig->getV()->mult($d)->mult($eig->getV()->inverse());
    }

    /**
     * Calculate e^M
     *
     * The algorithm uses a taylor series.
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function exp() : self
    {
        if ($this->m !== $this->n) {
            throw new InvalidDimensionException($this->m . 'x' . $this->n);
        }

        $eig = new EigenvalueDecomposition($this);
        $v   = $eig->getV();
        $d   = $eig->getD();

        $vInv = $v->inverse();

        for ($i = 0; $i < $this->m; ++$i) {
            $d->matrix[$i][$i] = \exp($d->matrix[$i][$i]);
        }

        return $v->mult($d)->mult($vInv);
    }

    /**
     * {@inheritdoc}
     */
    public function current() : int|float
    {
        $row = (int) ($this->position / $this->m);

        return $this->matrix[$row][$this->position - $row * $this->n];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet(mixed $offset) : mixed
    {
        if (!\is_int($offset)) {
            return 0;
        }

        $offset = (int) $offset;
        $row    = (int) ($offset / $this->m);

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
    public function key() : mixed
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
    public function offsetExists(mixed $offset) : bool
    {
        if (!\is_int($offset)) {
            return false;
        }

        $offset = (int) $offset;
        $row    = (int) ($offset / $this->m);

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
    public function offsetSet(mixed $offset, mixed $value) : void
    {
        if (!\is_int($offset) || !\is_numeric($value)) {
            return;
        }

        $offset                                        = (int) $offset;
        $row                                           = (int) ($offset / $this->m);
        $this->matrix[$row][$offset - $row * $this->n] = $value; /* @phpstan-ignore-line */
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset(mixed $offset) : void
    {
        if (!\is_int($offset)) {
            return;
        }

        $offset = (int) $offset;
        $row    = (int) ($offset / $this->m);
        unset($this->matrix[$row][$offset - $row * $this->n]);
    }
}
