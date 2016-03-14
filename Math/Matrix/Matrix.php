<?php

namespace phpOMS\Math\Matrix;

class Matrix implements ArrayAccess, Iterator 
{
    private $matrix = [];

    private $n = 0;
    private $m = 0;

    public function __construct(int $n, int $m) 
    {
        $this->n = $n;
        $this->m = $m;

        for($i = 0; $i < $m; $i++) {
            $this->matrix[$i] = array_fill(0, $n, 0);
        }
    } 

    public function setMatrix(array $matrix)
    {
        $this->matrix = $matrix;
    }

    public function set(int $n, int $m, $value)
    {
        $this->matrix[$n][$m] = $value;
    }

    public function get(int $n, int $m)
    {
        return $this->matrix[$n][$m];
    }

    public function transpose() : Matrix
    {
        $matrix = new Matrix($this->m, $this->n);
        $matrix->setMatrix(array_map(null, ...$matrix));

        return $matrix;
    }
}