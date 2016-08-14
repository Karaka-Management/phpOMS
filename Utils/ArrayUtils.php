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
namespace phpOMS\Utils;

/**
 * Array utils.
 *
 * @category   Framework
 * @package    phpOMS\Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ArrayUtils
{

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function unsetArray(string $path, array $data, string $delim) : array
    {
        $nodes  = explode($delim, $path);
        $prevEl = null;
        $el     = &$data;

        $node = null;

        foreach ($nodes as &$node) {
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
     * Check if needle exists in multidimensional array.
     *
     * @param string $path      Path to element
     * @param array  $data      Array
     * @param mixed  $value     Value to add
     * @param string $delim     Delimiter for path
     * @param bool   $overwrite Overwrite if existing
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function setArray(string $path, array $data, $value, string $delim, bool $overwrite = false) : array
    {
        $pathParts = explode($delim, $path);
        $current   = &$data;

        foreach ($pathParts as $key) {
            $current = &$current[$key];
        }

        if ($overwrite) {
            $current = $value;
        } else {
            if (is_array($current) && !is_array($value)) {
                $current[] = $value;
            } elseif (is_array($current) && is_array($value)) {
                $current += $value;
            } elseif (is_scalar($current) && $current !== null) {
                $current = [$current, $value];
            } else {
                $current = $value;
            }
        }

        return $data;
    }

    /**
     * Check if needle exists in multidimensional array.
     *
     * @param mixed $needle   Needle for search
     * @param array $haystack Haystack for search
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function inArrayRecursive($needle, array $haystack) : bool
    {
        $found = false;

        foreach ($haystack as $item) {
            if ($item === $needle) {
                return true;
            } elseif (is_array($item)) {
                $found = self::inArrayRecursive($needle, $item);

                if ($found) {
                    break;
                }
            }
        }

        return $found;
    }

    /**
     * Stringify array.
     *
     * @param array $array Array to stringify
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function stringify(array $array) : string
    {
        $str = '[';

        foreach ($array as $key => $value) {
            if (is_string($key)) {
                $key = '\'' . $key . '\'';
            }

            switch (gettype($value)) {
                case 'array':
                    $str .= $key . ' => ' . self::stringify($value) . ', ';
                    break;
                case 'integer':
                case 'double':
                case 'float':
                    $str .= $key . ' => ' . $value . ', ';
                    break;
                case 'string':
                    $str .= $key . ' => \'' . $value . '\'' . ', ';
                    break;
                case 'object':
                    $str .= $key . ' => ' . get_class($value['default']) . '()';
                    // TODO: implement object with parameters -> Reflection
                    break;
                case 'boolean':
                    $str .= $key . ' => ' . ($value['default'] ? 'true' : 'false') . ', ';
                    break;
                case 'NULL':
                    $str .= $key . ' => null, ';
                    break;
                default:
                    throw new \Exception('Unknown default type');
            }
        }

        return $str . ']';
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function arrayToCSV(array $data, string $delimiter = ';', string $enclosure = '"', string $escape = '\\') : string
    {
        $outstream = fopen('php://memory', 'r+');
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        fputcsv($outstream, $data, $delimiter, $enclosure, $escape);
        rewind($outstream);
        $csv = fgets($outstream);
        fclose($outstream);

        return $csv;
    }

    public static function getArg(string $id, array $args)
    {
        if(($key = array_search($id, $args)) === false || $key === count($args) - 1) {
            return null;
        }

        return $args[$key+1];
    }
}
