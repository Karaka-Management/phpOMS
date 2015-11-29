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
 * Multimap utils.
 *
 * @category   Framework
 * @package    Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class MultiMap
{

    /**
     * Stored values.
     *
     * @var array
     * @since 1.0.0
     */
    private $values = [];

    /**
     * Associated keys for values.
     *
     * @var array
     * @since 1.0.0
     */
    private $keys = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
    }

    /**
     * Add data.
     *
     * @param array $keys  Keys for value
     * @param mixed $value Value to store
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function add(array $keys, $value)
    {
        $this->values[] = $value;
        $id             = count($this->values) - 1;

        foreach ($keys as $key) {
            $this->keys[$key] = $id;
        }
    }

    /**
     * Get data.
     *
     * @param mixed $key Key used to identify value
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function get($key)
    {
        return $this->values[$this->keys[$key]] ?? null;
    }

    /**
     * Set existing key with data.
     *
     * @param mixed $key   Key used to identify value
     * @param mixed $value Value to store
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function set($key, $value) : \bool
    {
        if (isset($this->keys[$key])) {
            $this->values[$this->keys[$key]] = $value;

            return true;
        }

        return false;
    }

    /**
     * Remove value and all sibling keys based on key.
     *
     * @param mixed $key Key used to identify value
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function remove($key) : \bool
    {
        if (isset($this->keys[$key])) {
            $id = $this->keys[$key];

            foreach ($this->keys as $key => $ref) {
                if ($ref === $id) {
                    unset($this->keys[$key]);
                }
            }

            unset($this->values[$id]);

            return true;
        }

        return false;
    }

    /**
     * Remap key to a different value.
     *
     * Both keys need to exist in the multimap.
     *
     * @param mixed $old Old key
     * @param mixed $new New key
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function remap($old, $new) : \bool
    {
        if (isset($this->keys[$old]) && isset($this->keys[$new])) {
            $this->keys[$old] = $this->keys[$new];

            return true;
        }

        return false;
    }

    /**
     * Remove key.
     *
     * This only removes the value if no other key exists for this value.
     *
     * @param mixed $key Key used to identify value
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function removeKey($key) : \bool
    {
        if (isset($this->keys[$key])) {
            $id = $this->keys[$key];

            unset($this->keys[$key]);

            $unreferencd = true;

            foreach ($this->keys as $key => $value) {
                if ($value === $id) {
                    $unreferencd = false;
                    break;
                }
            }

            if ($unreferencd) {
                unset($this->values[$id]);
            }

            return true;
        }

        return false;
    }

    /**
     * Get all sibling keys.
     *
     * @param mixed $key Key to find siblings for
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getSiblings($key) : array
    {
        $siblings = [];

        if (isset($this->keys[$key])) {
            $id = $this->keys[$key];

            foreach ($this->keys as $found => $value) {
                if ($value === $id && $found !== $key) {
                    $siblings[] = $found;
                }
            }
        }

        return $siblings;
    }

    /**
     * Get all keys.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function keys() : array
    {
        return $this->keys;
    }

    /**
     * Get all values.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function values() : array
    {
        return $this->values;
    }

    /**
     * Count values.
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function count() : \int
    {
        return count($this->values);
    }
}
