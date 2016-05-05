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
namespace phpOMS\Utils\Compression;

/**
 * LZW compression class
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class LZW implements CompressionInterface
{

    /**
     * {@inheritdoc}
     */
    public function compress(string $source) : string
    {
        $wc         = '';
        $w          = '';
        $dictionary = [];
        $result     = [];
        $dictSize   = 256;

        for ($i = 0; $i < 256; $i += 1) {
            $dictionary[chr($i)] = $i;
        }

        $length = strlen($source);
        for ($i = 0; $i < $length; $i++) {
            $c  = $source[$i];
            $wc = $w . $c;

            if (array_key_exists($w . $c, $dictionary)) {
                $w = $w . $c;
            } else {
                array_push($result, $dictionary[$w]);
                $dictionary[$wc] = $dictSize++;
                $w               = (string) $c;
            }
        }

        if ($w !== '') {
            array_push($result, $dictionary[$w]);
        }

        return implode(',', $result);
    }

    /**
     * {@inheritdoc}
     */
    public function decompress(string $compressed) : string
    {
        $compressed = explode(',', $compressed);
        $dictionary = [];
        $entry      = '';
        $dictSize   = 256;

        for ($i = 0; $i < 256; $i++) {
            $dictionary[$i] = chr($i);
        }

        $w      = chr($compressed[0]);
        $result = $w;

        $count = count($compressed);
        for ($i = 1; $i < $count; $i++) {
            $k = $compressed[$i];

            if ($dictionary[$k]) {
                $entry = $dictionary[$k];
            } else {
                if ($k === $dictSize) {
                    $entry = $w . $w[0];
                } else {
                    return null;
                }
            }

            $result .= $entry;
            $dictionary[$dictSize++] = $w + $entry[0];
            $w                       = $entry;
        }

        return $result;
    }
}