<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Validation;

class BitcoinValidator 
{
    public static function validate(string $addr)  : bool
    {
        $decoded = decodeBase58($address);
 
        $d1 = hash("sha256", substr($decoded,0,21), true);
        $d2 = hash("sha256", $d1, true);
 
        if(substr_compare($decoded, $d2, 21, 4)) {
            return false;
        }

        return true;
    }

    public static function decodeBase58(string $input) : string
    {
        $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
 
        $out = array_fill(0, 25, 0);
        $len = strlen($input);

        for($i = 0; $i < $len; $i++){
                if(($p=strpos($alphabet, $input[$i]))===false){
                        throw new \Exception("invalid character found");
                }

                $c = $p;
                for ($j = 25; $j--; ) {
                        $c += (int)(58 * $out[$j]);
                        $out[$j] = (int)($c % 256);
                        $c /= 256;
                        $c = (int)$c;
                }

                if($c !== 0){
                    throw new \Exception("address too long");
                }
        }
 
        $result = "";
        foreach($out as $val){
                $result .= chr($val);
        }
 
        return $result;
    }
}