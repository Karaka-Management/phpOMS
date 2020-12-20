<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Utils
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils;

/**
 * Array utils.
 *
 * @package phpOMS\Utils
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
     * Get array value by argument id.
     *
     * Useful for parsing command line parsing
     *
     * @param string   $id   Id to find
     * @param string[] $args CLI command list
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getArg(string $id, array $args) : ?string
    {
        if (($key = \array_search($id, $args)) === false || $key === \count($args) - 1) {
            return null;
        }

        return \trim($args[(int) $key + 1], '" ');
    }

    /**
     * Check if flag is set
     *
     * @param string   $id   Id to find
     * @param string[] $args CLI command list
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function hasArg(string $id, array $args) : int
    {
        if (($key = \array_search($id, $args)) === false) {
            return -1;
        }

        return (int) $key;
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
    public static function arraySum(array $array, int $start = 0, int $count = 0) : int|float
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
    public static function power(array $values, int|float $exp = 2) : array
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
            if (!\is_array($value)) {
                if (!array_key_exists($key, $value2) || !\is_array($values2[$key])) {
                    $diff[$key] = $value;
                } else {
                    $subDiff = self::array_diff_assoc_recursive($value, $values2[$key]);
                    if (!empty($subDiff)) {
                        $diff[$key] = $subDiff;
                    }
                }
            } elseif ($values[$key] !== $value || !\array_key_exists($key, $values2)) {
                $diff[$key] == $value;
            }
        }

        return $diff;
    }
}
