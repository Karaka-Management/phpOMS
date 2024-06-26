<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\Parser\Php
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Php;

use phpOMS\Contract\SerializableInterface;

/**
 * Array parser class.
 *
 * Parsing/serializing arrays to and from php file
 *
 * @package phpOMS\Utils\Parser\Php
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ArrayParser
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
     * Serializing array (recursively).
     *
     * @param array $arr   Array to serialize
     * @param int   $depth Array depth
     *
     * @return string
     *
     * @since 1.0.0
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
     * @return string Returns the parsed value as string representation
     *
     * @throws \UnexpectedValueException Throws this exception if the value cannot be parsed (invalid data type)
     *
     * @since 1.0.0
     */
    public static function parseVariable(mixed $value, int $depth = 1) : string
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
        } elseif ($value instanceof SerializableInterface) {
            return self::parseVariable($value->serialize());
        } elseif ($value instanceof \JsonSerializable) {
            return self::parseVariable($value->jsonSerialize());
        } else {
            throw new \UnexpectedValueException();
        }
    }
}
