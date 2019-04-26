<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils\Compression
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\Compression;

/**
 * LZW compression class
 *
 * @package    phpOMS\Utils\Compression
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class LZW implements CompressionInterface
{

    /**
     * {@inheritdoc}
     */
    public function compress(string $source) : string
    {
        $w          = '';
        $dictionary = [];
        $result     = [];
        $dictSize   = 256;

        for ($i = 0; $i < 256; $i += 1) {
            $dictionary[\chr($i)] = $i;
        }

        $length = \strlen($source);
        for ($i = 0; $i < $length; ++$i) {
            $c  = $source[$i];
            $wc = $w . $c;

            if (\array_key_exists($w . $c, $dictionary)) {
                $w = $w . $c;
            } else {
                $result[]        = $dictionary[$w];
                $dictionary[$wc] = $dictSize++;
                $w               = $c;
            }
        }

        if ($w !== '') {
            $result[] = $dictionary[$w];
        }

        return \implode(',', $result);
    }

    /**
     * {@inheritdoc}
     */
    public function decompress(string $compressed) : string
    {
        $compressed = \explode(',', $compressed);
        $dictionary = [];
        $entry      = '';
        $dictSize   = 256;

        if (empty($compressed) || $compressed === false) {
            return '';
        }

        for ($i = 0; $i < 256; ++$i) {
            $dictionary[$i] = \chr($i);
        }

        $w      = \chr((int) $compressed[0]);
        $result = $dictionary[(int) ($compressed[0])] ?? 0;
        $count  = \count($compressed);

        for ($i = 1; $i < $count; ++$i) {
            $k = (int) $compressed[$i];

            if ($dictionary[$k]) {
                $entry = $dictionary[$k];
            } elseif ($k === $dictSize) {
                $entry = $w . $w[0];
            } else {
                throw new \Exception('Wrong dictionary size!' . $k . '.' . $dictSize); // @codeCoverageIgnore
            }

            $result                 .= $entry;
            $dictionary[$dictSize++] = $w . $entry[0];
            $w                       = $entry;
        }

        return $result;
    }
}
