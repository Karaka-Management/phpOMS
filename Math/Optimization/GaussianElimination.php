<?php

class GaussianElimination 
{
    private function swapRows(&$a, &$b, $r1, $r2)
    {
        if ($r1 == $r2) {
            return;
        }
     
        $tmp = $a[$r1];
        $a[$r1] = $a[$r2];
        $a[$r2] = $tmp;
     
        $tmp = $b[$r1];
        $b[$r1] = $b[$r2];
        $b[$r2] = $tmp;
    }
     
    public function solve(array $A, array $b, int $limit) : array
    {
        for ($col = 0; $col < $limit; $col++) {
            $j = $col;
            $max = $A[$j][$j];
     
            for ($i = $col + 1; $i < $limit; $i++) {
                $tmp = abs($A[$i][$col]);

                if ($tmp > $max) {
                    $j = $i;
                    $max = $tmp;
                }
            }
     
            $this->swapRows($A, $b, $col, $j);
     
            for ($i = $col + 1; $i < $limit; $i++) {
                $tmp = $A[$i][$col] / $A[$col][$col];

                for ($j = $col + 1; $j < $limit; $j++) {
                    $A[$i][$j] -= $tmp * $A[$col][$j];
                }

                $A[$i][$col] = 0;
                $b[$i] -= $tmp * $b[$col];
            }
        }

        $x = [];

        for ($col = $limit - 1; $col >= 0; $col--) {
            $tmp = $b[$col];

            for ($j = $limit - 1; $j > $col; $j--) {
                $tmp -= $x[$j] * $A[$col][$j];
            }

            $x[$col] = $tmp / $A[$col][$col];
        }

        return $x;
    }
}