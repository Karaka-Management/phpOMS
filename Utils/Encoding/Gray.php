<?php

class Gray {
    public static function encode(int $source) : int
    {
        return $source ^ ($source >> 1);
    }

    public static function decode(int $gray) : int
    {
        $source = $gray;

        while($gray >>= 1) {
            $source ^= $gray;
        }

        return $source;
    }
}