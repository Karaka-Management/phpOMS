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
namespace phpOMS\System;

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
     * File path.
     *
     * @var string
     * @since 1.0.0
     */
    public $file = null;

    /**
     * File path.
     *
     * @var array
     * @since 1.0.0
     */
    public $array = null;

    /**
     * Constructor.
     *
     * @param string $file     File path
     * @param string $arr_name Array to parse
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $file, string $arr_name)
    {
        if (file_exists($file)) {
            $this->file = $file;

            /** @noinspection PhpIncludeInspection */
            include $this->file;

            if (isset(${$arr_name})) {
                $this->array = ${$arr_name};
            }
        }
    }

    /**
     * Set or add new value.
     *
     * @param mixed $id  Array ID to add/edit
     * @param mixed $val Value to add/insert
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function set($id, $val)
    {
        $this->array[$id] = $val;
    }

    /**
     * Remove value.
     *
     * @param mixed $id Array ID to add/edit
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function delete($id)
    {
        unset($this->array[$id]);
    }

    /**
     * Saving array to file.
     *
     * @param string $name Name of new array
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function save(string $name)
    {
        $arr = '<' . '?php' . PHP_EOL
               . '$' . $name . ' = '
               . $this->serializeArray($this->array)
               . ';';

        file_put_contents($this->file, $arr);
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
    public function serializeArray(array $arr) : string
    {
        $stringify = '[' . PHP_EOL;

        foreach ($arr as $key => $val) {
            if(is_string($key)) {
                $key = '"' . $key . '"';
            }

            $stringify .= '    ' . $key . ' => ' . $this->arrayifyValue($val). ',' . PHP_EOL;

        }

        return $stringify . ']';
    }

    /**
     * Serialize array value.
     *
     * @param mixed $value Value to serialzie
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function arrayifyValue($value) : string
    {
        if(is_array($value)) {
            return '[' . PHP_EOL . $this->serializeArray($value) . PHP_EOL . ']';
        } elseif(is_string($value)) {
            return '"' . $value . '"';
        } elseif(is_scalar($value)) {
            return $value;
        } elseif(is_null($value)) {
            return 'null';
        } elseif($value instanceOf \Serializable) {
            return $this->arrayifyValue($value->serialize());
        } else {
            throw new \UnexpectedValueException();
        }
    }
}
