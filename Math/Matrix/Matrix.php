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
}