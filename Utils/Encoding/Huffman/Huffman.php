<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\Encoding\Huffman
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Encoding\Huffman;

/**
 * Gray encoding class
 *
 * @package phpOMS\Utils\Encoding\Huffman
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Huffman
{
    /**
     * Huffman dictionary.
     *
     * @var null|Dictionary
     * @since 1.0.0
     */
    private ?Dictionary $dictionary = null;

    /**
     * Get dictionary
     *
     * @return Dictionary
     *
     * @since 1.0.0
     */
    public function getDictionary() : ?Dictionary
    {
        return $this->dictionary;
    }

    /**
     * Set dictionary
     *
     * @param Dictionary $dictionary Huffman dictionary
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDictionary(Dictionary $dictionary) : void
    {
        $this->dictionary = $dictionary;
    }

    /**
     * Encode.
     *
     * @param string $source Source to encode
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function encode(string $source) : string
    {
        if (empty($source)) {
            return '';
        }

        if (!isset($this->dictionary)) {
            $this->dictionary = new Dictionary($source);
        }

        $binary = '';
        for ($i = 0; isset($source[$i]); ++$i) {
            $binary .= $this->dictionary->get($source[$i]);
        }

        $splittedBinaryString = \str_split('1' . $binary . '1', 8);
        $binary               = '';

        if ($splittedBinaryString === false) {
            return $binary; // @codeCoverageIgnore
        }

        foreach ($splittedBinaryString as $i => $c) {
            while (\strlen($c) < 8) {
                $c .= '0';
            }

            $binary .= \chr((int) \bindec($c));
        }

        return $binary;
    }

    /**
     * Decode.
     *
     * @param string $raw Raw to decode
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    public function decode(string $raw) : string
    {
        if (empty($raw) || $this->dictionary === null) {
            return '';
        }

        $binary    = '';
        $rawLength = \strlen($raw);
        $source    = '';

        for ($i = 0; $i < $rawLength; ++$i) {
            $decbin = \decbin(\ord($raw[$i]));

            while (\strlen($decbin) < 8) {
                $decbin = '0' . $decbin;
            }

            if ($i === 0) {
                $pos = \strpos($decbin, '1');

                if ($pos === false) {
                    throw new \Exception(); // @codeCoverageIgnore
                }

                $decbin = \substr($decbin, $pos + 1);
                if ($decbin === false) {
                    throw new \Exception(); // @codeCoverageIgnore
                }
            }

            if ($i + 1 === $rawLength) {
                $pos = \strrpos($decbin, '1');

                if ($pos === false) {
                    throw new \Exception(); // @codeCoverageIgnore
                }

                $decbin = \substr($decbin, 0, $pos);
                if ($decbin === false) {
                    throw new \Exception(); // @codeCoverageIgnore
                }
            }

            $binary .= $decbin;

            while (($entry = $this->dictionary->getEntry($binary)) !== null) {
                $source .= $entry;
            }
        }

        return $source;
    }
}
