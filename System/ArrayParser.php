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
     * @var \string
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
     * @param \string $file     File path
     * @param \string $arr_name Array to parse
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(\string $file, \string $arr_name)
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
     * @param \string $name Name of new array
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function save(\string $name)
    {
        $arr = '<' . '?php' . PHP_EOL
               . '$' . $name . ' = [' . PHP_EOL
               . $this->serializeArray($this->array)
               . '];';

        file_put_contents($this->file, $arr);
    }

    /**
     * Serializing array (recursively).
     *
     * @param array $arr Array to serialize
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function serializeArray(array $arr) : \string
    {
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                if (is_string($key)) {
                    return '"' . $key . '" => [' . PHP_EOL . $this->serializeArray($val) . PHP_EOL . '],' . PHP_EOL;
                } else {
                    return $key . ' => [' . PHP_EOL . $this->serializeArray($val) . PHP_EOL . '],' . PHP_EOL;
                }
            } elseif (is_null($val)) {
                if (is_string($key)) {
                    return '"' . $key . '" => null';
                } else {
                    return $key . ' => null,' . PHP_EOL;
                }
            } else {
                if (is_string($key)) {
                    return '"' . $key . '" => ' . $val . ',' . PHP_EOL;
                } else {
                    return $key . ' => ' . $val . ',' . PHP_EOL;
                }
            }
        }

        return '';
    }
}
