<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Php;

/**
 * Array parser class.
 *
 * Parsing/serializing arrays to and from php file
 *
 * @category   Framework
 * @package    phpOMS\Utils\Parser
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ArrayParser
{
    /**
     * Serializing array (recursively).
     *
     * @param array $arr Array to serialize
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function serializeArray(array $arr) : string
    {
        $stringify = '[' . PHP_EOL;

        foreach ($arr as $key => $val) {
            if (is_string($key)) {
                $key = '"' . $key . '"';
            }

            $stringify .= '    ' . $key . ' => ' . MemberParser::parseVariable($val) . ',' . PHP_EOL;

        }

        return $stringify . ']';
    }
}
