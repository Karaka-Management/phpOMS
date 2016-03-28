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
namespace phpOMS\Utils\Parser\Php;

/**
 * Array parser class.
 *
 * Parsing/serializing arrays to and from php file
 *
 * @category   System
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ArrayParser
{
    /**
     * Saving array to file.
     *
     * @param string $name Name of new array
     * @param array $arr Array to parse
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function createFile(string $name, array $arr) : string
    {
        $out = '<' . '?php' . PHP_EOL
               . '$' . $name . ' = ' . self::serializeArray($this->arr) . ';';

        return $out;
    }

    /**
     * Serializing array (recursively).
     *
     * @param array $arr Array to serialize
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function serializeArray(array $arr) : string
    {
        $stringify = '[' . PHP_EOL;

        foreach ($arr as $key => $val) {
            if(is_string($key)) {
                $key = '"' . $key . '"';
            }

            $stringify .= '    ' . $key . ' => ' . MemberParser::parseVariable($val). ',' . PHP_EOL;

        }

        return $stringify . ']';
    }
}
