<?php

namespace phpOMS\Math\Matrix;

class Matrix implements ArrayAccess, Iterator 
{
    private $matrix = [];

    private $n = 0;
    private $m = 0;

    public function __construct(int $m, int $n = 1) 
    {
        $this->n = $n;
        $this->m = $m;

        for($i = 0; $i < $m; $i++) {
            $this->matrix[$i] = array_fill(0, $n, 0);
        }
    } 

    public function setMatrix(array $matrix)
    {
        if($this->m !== count($matrix) || $this->n !== count($matrix[0])) {
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
        $matrix->setMatrix(array_map(null, ...$matrix));

        return $matrix;
    }

    public function mult($value) : Matrix
    {
        if($value instanceOf Matrix) {
            return $this->multMatrix($value);
        } elseif(is_scalar($value)) {
            return $this->multScalar($value);
        }
    }

    private function multMatrix(Matrix $matrix) : Matrix
    {
        $nDim = $matrix->getN();
        $mDim = $matrix->getM();

        if($this->n !== $mDim) {
            throw new \Exception('Dimension');
        }

        $matrixArr = $matrix->getMatrix();
        $newMatrix = new Matrix($this->m, $nDim);
        $newMatrixArr = $newMatrix->getMatrix();

        for($i = 0; $i < $this->m; $i++) { // Row of $this
            for($c = 0; $c < $nDim; $c++) { // Column of $matrix
                $temp = 0;

                for($j = 0; $j < $mDim; $i++) { // Row of $matrix
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

        foreach($newMatrixArr as $i => $vector) {
            foreach($vector as $j => $value) {
                $newMatrixArr[$i][$j] *= $value;
            }
        }

        $newMatrix = new Matrix($this->m, $this->n);
        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    public function add($value) : Matrix
    {
        if($value instanceOf Matrix) {
            return $this->addMatrix($value);
        } elseif(is_scalar($value)) {
            return $this->addScalar($value);
        }
    }

    public function sub($value) : Matrix
    {
        if($value instanceOf Matrix) {
            return $this->add($this->multMatrix(-1));
        } elseif(is_scalar($value)) {
            return $this->addScalar(-$value);
        }
    }

    private function addMatrix(Matrix $value) : Matrix
    {
        if($this->m !== $value->getM() || $this->n !== $value->getN()) {
            throw new \Exception('Dimension');
        }

        $matrixArr = $value->getMatrix();
        $newMatrixArr = $this->matrix;

        foreach($newMatrixArr as $i => $vector) {
            foreach($vector as $j => $value) {
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

        foreach($newMatrixArr as $i => $vector) {
            foreach($vector as $j => $value) {
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
        $matrix->setMatrix($this->upperTrianglize($matrixArr));

        return $matrix;
    }

    public function lowerTriangular() : Matrix 
    {
        // todo: implement
        return new Matrix($this->m, $this->n);
    }

    public function diag() : Matrix
    {

    }

    public function inverse(int $algorithm = InversionType::GAUSS_JORDAN) : Matrix
    {
        if($this->n !== $this->m) {
            throw new \Exception('Dimension');
        }

        switch($algorithm) {
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
        for($i = 0; $i < $this->n; $i++) {
            for($j = $this->n; $j < $this->n * 2; $j++) {

                if($j === ($i + $this->n)) {
                    $newMatrixArr[$i][$j] = 1;
                } else {
                    $newMatrixArr[$i][$j] = 0;
                }
            }
        }

        // todo: maybe replace by triangulize/vice versa????!!!?!?!
        // pivoting
        for($i = $this->n - 1; $i > 0; $i--) {
            if ($newMatrixArr[$i - 1][0] < $newMatrixArr[$i][0]) {
                for($j = 0; $j < $this->n * 2; $j++) {
                    $temp = $newMatrixArr[$i][$j];
                    $newMatrixArr[$i][$j] = $newMatrixArr[$i-1][$j];
                    $newMatrixArr[$i-1][$j] = $temp;
                }
            }
        }

        /* create diagonal matrix */
        for($i = 0; $i < $this->n; $i++) {
            for($j = 0; $j < $this->n; $j++) {
                if ($j !== $i) {
                    $temp = $newMatrixArr[$j][$i] / $newMatrixArr[$i][$i];

                    for($c = 0; $c < $this->n * 2; $c++) {
                        $newMatrixArr[$j][$c] -= $newMatrixArr[$i][$c] * $temp;
                    }
                }
            }
        }

        /* create unit matrix */
        for($i = 0; $i < $this->n; $i++) {
            $temp = $newMatrixArr[$i][$i];

            for($j = 0; $j < $this->n * 2; $j++) {
                $newMatrixArr[$i][$j] = $newMatrixArr[$i][$j] / $temp;
            }
        }

        /* removing identity matrix */
        for($i = 0; $i < $this->n; $i++) {
            $newMatrixArr[$i] = array_slice($newMatrixArr[$i], $this->n);
        }

        $newMatrix = new Matrix($this->n, $this->n);
        $newMatrix->setMatrix($newMatrixArr);

        return $newMatrix;
    }

    private function decompositionCholesky() : Matrix 
    {
        $newMatrix = new Matrix($this->n, $this->n);
        $newMatrixArr = $newMatrix->getMatrix();

        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $i+1; $j++) {
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

    private function upperTrianglize(array &$arr) : int
    {
        $n = count($arr);
        $sign = 1;

        for ($i = 0; $i < $n; $i++) {
            $max = 0;
     
            for ($j = $i; $j < $n; $j++) {
                if (abs($arr[$j][$i]) > abs($arr[$max][$i])) {
                    $max = $j;
                }
            }
     
            if ($max) {
                $sign = -$sign;
                $temp = $arr[$i];
                $arr[$i] = $arr[$max], $arr[$max] = $temp;
            }
     
            if (!$arr[$i][$i]) {
                return 0;
            }
     
            for ($j = $i + 1; $j < $n; $j++) {
                $r = $arr[$j][$i] / $arr[$i][$i];

                if (!$r) {
                    continue;
                }
     
                for ($c = $i; $c < $n; $c ++) {
                    $arr[$j][$c] -= $arr[$i][$c] * $r;
                }
            }
        }

        return $sign;
    } 

    public function det()
    {
        if($this->n === 1) {
            return $this->matrix[0][0];
        }

        $trianglize = $this->matrix;
        $prod = $this->(upperTrianglize($trianglize));

        for($i = 0; $i < $this->n; $i++) {
            $prod *= $trianglize[$i][$i];
        }

        return $prod;
    }
}