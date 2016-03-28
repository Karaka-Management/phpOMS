<?php

namespace phpOMS\Utils;

class Permutation 
{
    /**
     * usage: permut(['a', 'b', 'c']);
     */
    public static function permut(array $toPermute, array $result = []) : array
    {
        $permutations = [];

        if(empty($toPermute)){
            $permutations[] = implode('', $result);
        } else{
            foreach($toPermute as $key => $val){
                $newArr = $toPermute;
                $newres = $result;
                $newres[] = $val;
                unset($newArr[$key]);
                $permutations += permut($newArr, $newres);        
            }
        }

        return $permutations;
    }
}