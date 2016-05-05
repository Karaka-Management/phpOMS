<?php

namespace phpOMS\Math\Matrix;

class Matrix implements \ArrayAccess, \Iterator
{
    protected $matrix = [];

    protected $n = 0;
    protected $m = 0;

    public function __construct(int $m, int $n = 1)
    {
        $this->n = $n;
        $this->m = $m;

        for ($i = 0; $i < $m; $i++) {
            $this->matrix[$i] = array_fill(0, $n, 0);
        }
    }

    public function setMatrix(array $matrix)
    {
        if ($this->m !== count($matrix) || $this->n !== count($matrix[0])) {
            throw new \Exception('Dimension');
        }

        $this->matrix = $matrix;
    }

    public function getMatrix() : array
    {
        return $this->matrix;
    }

    public function set(int $m, int $n, $value)
    {
        $this->matrix[$m][$n] = $value;
    }

    public function get(int $m, int $n)
    {
        return $this->matrix[$m][$n];
    }

    public function transpose() : Matrix
    {
        $matrix = new Matrix($this->n, $this->m);
        $matrix->setMatrix(array_map(null, $matrix->getMatrix()));

        return $matrix;
    }

    public function mult($value) : Matrix
    {
        if ($value instanceOf Matrix) {
            return $this->multMatrix($value);
        } elseif (is_scalar($value)) {
            return $this->multScalar($value);
        }

        throw new \Exception();
    }

    private function multMatrix(Matrix $matrix) : Matrix
    {
        $nDim = $matrix->getN();
        $mDim = $matrix->getM();

        if ($this->n !== $mDim) {
            throw new \Exception('Dimension');
        }

        $matrixArr    = $matrix->getMatrix();
        $newMatrix    = new Matrix($this->m, $nDim);
        $newMatrixArr = $newMatrix->getMatrix();

        for ($i = 0; $i < $this->m; $i++) { // Row of $this
            for ($c = 0; $c < $nDim; $c++) { // Column of $matrix
                $temp = 0;

                for ($j = 0; $j < $mDim; $i++) { // Row of $matrix
                    $temp += $this->matrix[$i][$j] * $matrixArr[$j][$c];
                }

                $newMatrixArr[$i][$c] = $temp;
            }
        }

        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    private function multScalar($value) : Matrix
    {
        $newMatrixArr = $this->matrix;

        foreach ($newMatrixArr as $i => $vector) {
            foreach ($vector as $j => $value) {
                $newMatrixArr[$i][$j] *= $value;
            }
        }

        $newMatrix = new Matrix($this->m, $this->n);
        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    public function add($value) : Matrix
    {
        if ($value instanceOf Matrix) {
            return $this->addMatrix($value);
        } elseif (is_scalar($value)) {
            return $this->addScalar($value);
        }

        throw new \Exception();
    }

    public function sub($value) : Matrix
    {
        if ($value instanceOf Matrix) {
            return $this->add($this->mult(-1));
        } elseif (is_scalar($value)) {
            return $this->add(-$value);
        }

        throw new \Exception();
    }

    private function addMatrix(Matrix $value) : Matrix
    {
        if ($this->m !== $value->getM() || $this->n !== $value->getN()) {
            throw new \Exception('Dimension');
        }

        $matrixArr    = $value->getMatrix();
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

    private function addScalar($value) : Matrix
    {
        $newMatrixArr = $this->matrix;

        foreach ($newMatrixArr as $i => $vector) {
            foreach ($vector as $j => $value) {
                $newMatrixArr[$i][$j] += $value;
            }
        }

        $newMatrix = new Matrix($this->m, $this->n);
        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    public function upperTriangular() : Matrix
    {
        $matrix = new Matrix($this->n, $this->n);

        $matrixArr = $this->matrix;
        $this->upperTrianglize($matrixArr);

        $matrix->setMatrix($matrixArr);

        return $matrix;
    }

    public function lowerTriangular() : Matrix
    {
        // todo: implement
        return new Matrix($this->m, $this->n);
    }

    public function inverse(int $algorithm = InversionType::GAUSS_JORDAN) : Matrix
    {
        if ($this->n !== $this->m) {
            throw new \Exception('Dimension');
        }

        switch ($algorithm) {
            case InversionType::GAUSS_JORDAN:
                return $this->inverseGaussJordan();
            default:
                throw new \Exception('');
        }
    }

    private function inverseGaussJordan() : Matrix
    {
        $newMatrixArr = $this->matrix;

        // extending matrix by identity matrix
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = $this->n; $j < $this->n * 2; $j++) {

                if ($j === ($i + $this->n)) {
                    $newMatrixArr[$i][$j] = 1;
                } else {
                    $newMatrixArr[$i][$j] = 0;
                }
            }
        }

        $mDim = count($newMatrixArr);
        $nDim = count($newMatrixArr[0]);

        // pivoting
        $newMatrixArr = $this->diag($newMatrixArr);

        /* create unit matrix */
        for ($i = 0; $i < $mDim; $i++) {
            $temp = $newMatrixArr[$i][$i];

            for ($j = 0; $j < $nDim; $j++) {
                $newMatrixArr[$i][$j] = $newMatrixArr[$i][$j] / $temp;
            }
        }

        /* removing identity matrix */
        for ($i = 0; $i < $mDim; $i++) {
            $newMatrixArr[$i] = array_slice($newMatrixArr[$i], $mDim);
        }

        $newMatrix = new Matrix($this->n, $this->n);
        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    private function decompositionCholesky() : Matrix
    {
        $newMatrix    = new Matrix($this->n, $this->n);
        $newMatrixArr = $newMatrix->getMatrix();

        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $i + 1; $j++) {
                $temp = 0;

                for ($c = 0; $c < $j; $c++) {
                    $temp += $newMatrixArr[$i][$c] * $newMatrixArr[$j][$c];
                }

                $newMatrixArr[$i][$j] = ($i == $j) ? sqrt($this->matrix[$i][$i] - $temp) : (1 / $newMatrixArr[$j][$j] * ($this->matrix[$i][$j] - $temp));
            }
        }

        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    private function diag(array $arr) : array
    {
        $mDim = count($arr);
        $nDim = count($arr[0]);

        for ($i = $mDim - 1; $i > 0; $i--) {
            if ($arr[$i - 1][0] < $arr[$i][0]) {
                for ($j = 0; $j < $nDim; $j++) {
                    $temp            = $arr[$i][$j];
                    $arr[$i][$j]     = $arr[$i - 1][$j];
                    $arr[$i - 1][$j] = $temp;
                }
            }
        }

        /* create diagonal matrix */
        for ($i = 0; $i < $mDim; $i++) {
            for ($j = 0; $j < $mDim; $j++) {
                if ($j !== $i) {
                    $temp = $arr[$j][$i] / $arr[$i][$i];

                    for ($c = 0; $c < $nDim; $c++) {
                        $arr[$j][$c] -= $arr[$i][$c] * $temp;
                    }
                }
            }
        }

        return $arr;
    }

    private function upperTrianglize(array &$arr) : int
    {
        $n    = count($arr);
        $sign = 1;

        for ($i = 0; $i < $n; $i++) {
            $max = 0;

            for ($j = $i; $j < $n; $j++) {
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

            for ($j = $i + 1; $j < $n; $j++) {
                $r = $arr[$j][$i] / $arr[$i][$i];

                if (!$r) {
                    continue;
                }

                for ($c = $i; $c < $n; $c++) {
                    $arr[$j][$c] -= $arr[$i][$c] * $r;
                }
            }
        }

        return $sign;
    }

    public function det()
    {
        if ($this->n === 1) {
            return $this->matrix[0][0];
        }

        $trianglize = $this->matrix;
        $prod       = $this->upperTrianglize($trianglize);

        for ($i = 0; $i < $this->n; $i++) {
            $prod *= $trianglize[$i][$i];
        }

        return $prod;
    }

    /**
     * Return the current element
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        // TODO: Implement current() method.
    }

    /**
     * Move forward to next element
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        // TODO: Implement next() method.
    }

    /**
     * Return the key of the current element
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        // TODO: Implement key() method.
    }

    /**
     * Checks if current position is valid
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        // TODO: Implement valid() method.
    }

    /**
     * Rewind the Iterator to the first element
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    /**
     * Whether a offset exists
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     * @return boolean true on success or false on failure.
     *                      </p>
     *                      <p>
     *                      The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * Offset to set
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}