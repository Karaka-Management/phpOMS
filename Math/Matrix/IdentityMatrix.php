<?php

namespace phpOMS\Math;

class IdentityMatrix extends Matrix {
    public function __constrcut(int $n) 
    {
        $this->n = $n;
        $this->m = $n;

        for($i = 0; $i < $n; $i++) {
            $this->matrix[$i] = array_fill(0, $n, 0);
            $this->matrix[$i][$i] = 1;
        }
    }   
}