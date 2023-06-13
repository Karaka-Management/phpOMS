<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils;

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Array utils.
 *
 * @package phpOMS\Utils
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ArrayUtils
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Check if needle exists in multidimensional array.
     *
     * @param string $path  Path to element
     * @param array  $data  Array
     * @param string $delim Delimiter for path
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function unsetArray(string $path, array $data, string $delim = '/') : array
    {
        $nodes  = \explode($delim, \trim($path, $delim));
        $prevEl = null;
        $el     = &$data;
        $node   = null;

        if ($nodes === false) {
            throw new \Exception(); // @codeCoverageIgnore
        }

        foreach ($nodes as $node) {
            $prevEl = &$el;

            if (!isset($el[$node])) {
                break;
            }

            $el = &$el[$node];
        }

        if ($prevEl !== null) {
            unset($prevEl[$node]);
        }

        return $data;
    }

    /**
     * Calculate the range of the array
     *
     * @param int[]|float[] $values Numeric values
     *
     * @return int|float Range of the array
     *
     * @since 1.0.0
     */
    public static function range(array $values) : int|float
    {
        return \max($values) - \min($values);
    }

    /**
     * Set element in array by path
     *
     * @param string $path      Path to element
     * @param array  $data      Array
     * @param mixed  $value     Value to add
     * @param string $delim     Delimiter for path
     * @param bool   $overwrite Overwrite if existing
     *
     * @return array
     *
     * @throws \Exception This exception is thrown if the path is corrupted
     *
     * @since 1.0.0
     */
    public static function setArray(string $path, array $data, mixed $value, string $delim = '/', bool $overwrite = false) : array
    {
        $pathParts = \explode($delim, \trim($path, $delim));
        $current   = &$data;

        if ($pathParts === false) {
            throw new \Exception(); // @codeCoverageIgnore
        }

        foreach ($pathParts as $key) {
            $current = &$current[$key];
        }

        if ($overwrite) {
            $current = $value;
        } elseif (\is_array($current) && !\is_array($value)) {
            $current[] = $value;
        } elseif (\is_array($current) && \is_array($value)) {
            $current = \array_merge($current, $value);
        } elseif (\is_scalar($current) && $current !== null) {
            $current = [$current, $value];
        } else {
            $current = $value;
        }

        return $data;
    }

    /**
     * Get element of array by path
     *
     * @param string $path  Path to element
     * @param array  $data  Array
     * @param string $delim Delimiter for path
     *
     * @return mixed
     *
     * @throws \Exception This exception is thrown if the path is corrupted
     *
     * @since 1.0.0
     */
    public static function getArray(string $path, array $data, string $delim = '/') : mixed
    {
        $pathParts = \explode($delim, \trim($path, $delim));
        $current   = $data;

        if ($pathParts === false) {
            throw new \Exception(); // @codeCoverageIgnore
        }

        foreach ($pathParts as $key) {
            if (!isset($current[$key])) {
                return null;
            }

            $current = $current[$key];
        }

        return $current;
    }

    /**
     * Check if needle exists in multidimensional array.
     *
     * @param mixed $needle   Needle for search
     * @param array $haystack Haystack for search
     * @param mixed $key      Key that has to match (optional)
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function inArrayRecursive(mixed $needle, array $haystack, $key = null) : bool
    {
        $found = false;

        foreach ($haystack as $k => $item) {
            if ($item === $needle && ($key === null || $key === $k)) {
                return true;
            } elseif (\is_array($item)) {
                $found = self::inArrayRecursive($needle, $item, $key);

                if ($found) {
                    return true;
                }
            }
        }

        return $found;
    }

    /**
     * Check if any of the needles are in the array
     *
     * @param array $needles  Needles for search
     * @param array $haystack Haystack for search
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function anyInArray(array $needles, array $haystack) : bool
    {
        foreach ($needles as $needle) {
            if (\in_array($needle, $haystack)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if all of the needles are in the array
     *
     * @param array $needles  Needles for search
     * @param array $haystack Haystack for search
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function allInArray(array $needles, array $haystack) : bool
    {
        foreach ($needles as $needle) {
            if (!\in_array($needle, $haystack)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Convert array to csv string.
     *
     * @param array  $data      Data to convert
     * @param string $delimiter Delim to use
     * @param string $enclosure Enclosure to use
     * @param string $escape    Escape to use
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function arrayToCsv(array $data, string $delimiter = ';', string $enclosure = '"', string $escape = '\\') : string
    {
        $outstream = \fopen('php://memory', 'r+');

        if ($outstream === false) {
            throw new \Exception(); // @codeCoverageIgnore
        }

        foreach ($data as $line) {
            /** @noinspection PhpMethodParametersCountMismatchInspection */
            \fputcsv($outstream, $line, $delimiter, $enclosure, $escape);
        }

        \rewind($outstream);
        $csv = \stream_get_contents($outstream);
        \fclose($outstream);

        return $csv === false ? '' : $csv;
    }

    /**
     * Convert array to xml string.
     *
     * @param array             $data Data to convert
     * @param \SimpleXMLElement $xml  XML parent
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function arrayToXml(array $data, \SimpleXMLElement $xml = null) : string
    {
        $xml ??= new \SimpleXMLElement('<root/>');

        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                self::arrayToXml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, \htmlspecialchars($value));
            }
        }
        return (string) $xml->asXML();
    }

    /**
     * Get array value by argument id.
     *
     * Useful for parsing command line parsing
     *
     * @template T
     *
     * @param string               $id   Id to find
     * @param array<string|int, T> $args CLI command list
     *
     * @return null|T
     *
     * @since 1.0.0
     */
    public static function getArg(string $id, array $args) : mixed
    {
        if (\is_numeric($id)) {
            return $args[(int) $id] ?? null;
        }

        if (($key = \array_search($id, $args)) === false || $key === \count($args) - 1) {
            return null;
        }

        return \trim($args[(int) $key + 1], '" ');
    }

    /**
     * Check if flag is set
     *
     * @param string                   $id   Id to find
     * @param array<string|int, mixed> $args CLI command list
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function hasArg(string $id, array $args) : int
    {
        $t = \array_search($id, $args);
        return ($key = \array_search($id, $args)) === false
            ? -1
            : (int) $key;
    }

    /**
     * Flatten array
     *
     * Reduces multi dimensional array to one dimensional array. Flatten tries to maintain the index as far as possible.
     *
     * @param array $array Multi dimensional array to flatten
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function arrayFlatten(array $array) : array
    {
        // see collection collapse as alternative?!
        $flat  = [];
        $stack = \array_values($array);

        while (!empty($stack)) {
            $value = \array_shift($stack);

            if (\is_array($value)) {
                $stack = \array_merge(\array_values($value), $stack);
            } else {
                $flat[] = $value;
            }
        }

        return $flat;
    }

    /**
     * Sum of array elements
     *
     * @param array $array Array to sum
     * @param int   $start Start index
     * @param int   $count Amount of elements to sum
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    public static function arraySum(array $array, int $start = 0, int $count = 0) : int | float
    {
        $count = $count === 0 ? \count($array) : $start + $count;
        $sum   = 0;
        $array = \array_values($array);

        for ($i = $start; $i <= $count - 1; ++$i) {
            $sum += $array[$i];
        }

        return $sum;
    }

    /**
     * Sum multi dimensional array
     *
     * @param array $array Multi dimensional array to flatten
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function arraySumRecursive(array $array) : mixed
    {
        return \array_sum(self::arrayFlatten($array));
    }

    /**
     * Applying abs to every array value
     *
     * @param array<int|float> $values Numeric values
     *
     * @return array<int|float>
     *
     * @since 1.0.0
     */
    public static function abs(array $values) : array
    {
        $abs = [];

        foreach ($values as $value) {
            $abs[] = \abs($value);
        }

        return $abs;
    }

    /**
     * Power all values in array.
     *
     * @param array<int|float> $values Values to square
     * @param float            $exp    Exponent
     *
     * @return float[]
     *
     * @since 1.0.0
     */
    public static function power(array $values, int | float $exp = 2) : array
    {
        $squared = [];

        foreach ($values as $value) {
            $squared[] = $value ** $exp;
        }

        return $squared;
    }

    /**
     * Sqrt all values in array.
     *
     * @param array<int|float> $values Values to sqrt
     *
     * @return array<int|float>
     *
     * @since 1.0.0
     */
    public static function sqrt(array $values) : array
    {
        $squared = [];

        foreach ($values as $value) {
            $squared[] = \sqrt($value);
        }

        return $squared;
    }

    /**
     * Get the associative difference of two arrays.
     *
     * @param array $values1 Array 1
     * @param array $values2 Array 2
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function array_diff_assoc_recursive(array $values1, array $values2) : array
    {
        $diff = [];
        foreach ($values1 as $key => $value) {
            if (\is_array($value)) {
                if (!\array_key_exists($key, $values2) || !\is_array($values2[$key])) {
                    $diff[$key] = $value;
                } else {
                    $subDiff = self::array_diff_assoc_recursive($value, $values2[$key]);
                    if (!empty($subDiff)) {
                        $diff[$key] = $subDiff;
                    }
                }
            } elseif (!\array_key_exists($key, $values2) || $values2[$key] !== $value) {
                $diff[$key] = $value;
            }
        }

        return $diff;
    }

    /**
     * Get the dot product of two arrays
     *
     * @param array $value1 Value 1 is a matrix or a vector
     * @param array $value2 Value 2 is a matrix or vector (cannot be a matrix if value1 is a vector)
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function dot(array $value1, array $value2) : int|float|array
    {
        $m1 = \count($value1);
        $n1 = ($isMatrix1 = \is_array($value1[0])) ? \count($value1[0]) : 1;

        $m2 = \count($value2);
        $n2 = ($isMatrix2 = \is_array($value2[0])) ? \count($value2[0]) : 1;

        $result = null;

        if ($isMatrix1 && $isMatrix2) {
            if ($m2 !== $n1) {
                throw new InvalidDimensionException($m2 . 'x' . $n2 . ' not compatible with ' . $m1 . 'x' . $n1);
            }

            $result = [[]];
            for ($i = 0; $i < $m1; ++$i) { // Row of 1
                for ($c = 0; $c < $n2; ++$c) { // Column of 2
                    $temp = 0;

                    for ($j = 0; $j < $m2; ++$j) { // Row of 2
                        $temp += $value1[$i][$j] * $value2[$j][$c];
                    }

                    $result[$i][$c] = $temp;
                }
            }
        } elseif (!$isMatrix1 && !$isMatrix2) {
            if ($m1 !== $m2) {
                throw new InvalidDimensionException($m1 . ' vs. ' . $m2);
            }

            $result = 0;
            for ($i = 0; $i < $m1; ++$i) {
                $result += $value1[$i] * $value2[$i];
            }
        } elseif ($isMatrix1 && !$isMatrix2) {
            $result = [];
            for ($i = 0; $i < $m1; ++$i) { // Row of 1
                $temp = 0;

                for ($c = 0; $c < $m2; ++$c) { // Row of 2
                    $temp += $value1[$i][$c] * $value2[$c];
                }

                $result[$i] = $temp;
            }
        } else {
            throw new \InvalidArgumentException();
        }

        return $result;
    }

    /**
     * Calculate the vector corss product
     *
     * @param array $vector1 First 3 vector
     * @param array $vector2 Second 3 vector
     *
     * @return array<int, int|float>
     *
     * @since 1.0.0
     */
    public function cross3(array $vector1, array $vector2) : array
    {
        return [
            $vector1[1] * $vector2[2] - $vector1[2] * $vector2[1],
            $vector1[2] * $vector2[0] - $vector1[0] * $vector2[2],
            $vector1[0] * $vector2[1] - $vector1[1] * $vector2[0],
        ];
    }
}
