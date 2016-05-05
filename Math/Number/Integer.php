<?php
use phpOMS\Math\Number\Prime;

class Integer
{
    public static function isInteger($value) : bool
    {
        return is_int($value);
    }

    /**
     * Greatest common diviser.
     *
     * @param int $n Number one
     * @param int $m Number two
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function greatestCommonDivisor(int $n, int $m) : int
    {
        while (true) {
            if ($n === $m) {
                return $m;
            }
            if ($n > $m) {
                $n -= $m;
            } else {
                $m -= $n;
            }
        }

        return 1;
    }

    public static function trialFactorization(int $value)
    {
        if ($value < 2) {
            return [];
        }

        $factors = [];
        $primes  = Prime::sieveOfEratosthenes((int) $value ** 0.5);

        foreach ($primes as $prime) {
            if ($prime * $prime > $value) {
                break;
            }

            while ($value % $prime === 0) {
                $factors[] = $prime;
                $value /= $prime;
            }
        }

        if ($value > 1) {
            $factors[] = $value;
        }

        return $factors;
    }

    public static function pollardsRho($value, $x = 2, $factor = 1, $cycleSize = 2, $xFixed = 2)
    {
        while ($factor === 1) {
            for ($i = 1; $i < $cycleSize && $factor <= 1; $i++) {
                $x      = ($x * $x + 1) % $value;
                $factor = self::greatestCommonDivisor($x - $xFixed, $value);
            }

            $cycleSize *= 2;
            $xFixed = $x;
        }

        return $factor;
    }

    public static function fermatFactor(int $value)
    {
        $a  = $value;
        $b2 = $a * $a - $value;

        while (abs((int) round(sqrt($b2), 0) - sqrt($b2)) > 0.0001) {
            $a += 1;
            $b2 = $a * $a - $value;
        }

        return $a - sqrt($b2);
    }
}