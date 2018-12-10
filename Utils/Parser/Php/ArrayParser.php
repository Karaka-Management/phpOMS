<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils\Parser\Php
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Php;

/**
 * Array parser class.
 *
 * Parsing/serializing arrays to and from php file
 *
 * @package    phpOMS\Utils\Parser\Php
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class ArrayParser
{
    /**
     * Serializing array (recursively).
     *
     * @param array $arr   Array to serialize
     * @param int   $depth Array depth
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function serializeArray(array $arr, int $depth = 1) : string
    {
        $stringify = '[' . "\n";

        foreach ($arr as $key => $val) {
            if (\is_string($key)) {
                $key = '\'' . \str_replace('\'', '\\\'', $key) . '\'';
            }

            $stringify .= \str_repeat(' ', $depth * 4) . $key . ' => ' . self::parseVariable($val, $depth + 1) . ',' . "\n";
        }

        return $stringify . \str_repeat(' ', ($depth - 1) * 4) . ']';
    }

    /**
     * Serialize value.
     *
     * @param mixed $value Value to serialzie
     * @param int   $depth Array depth
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function parseVariable($value, int $depth = 1) : string
    {
        if (\is_array($value)) {
            return self::serializeArray($value, $depth);
        } elseif (\is_string($value)) {
            return '\'' . \str_replace('\'', '\\\'', $value) . '\'';
        } elseif (\is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif ($value === null) {
            return 'null';
        } elseif (\is_float($value)) {
            return \rtrim(\rtrim(\number_format($value, 5, '.', ''), '0'), '.');
        } elseif (\is_scalar($value)) {
            return (string) $value;
        } elseif ($value instanceof \Serializable) {
            return self::parseVariable($value->serialize());
        } elseif ($value instanceof \JsonSerializable) {
            return self::parseVariable($value->jsonSerialize());
        } else {
            throw new \UnexpectedValueException();
        }
    }
}
