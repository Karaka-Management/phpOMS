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
namespace phpOMS\Config;

/**
 * Options class.
 *
 * @category   Framework
 * @package    phpOMS\Config
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
interface OptionsInterface
{

    /**
     * Is this key set.
     *
     * @param mixed $key Key to check for existence
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function exists($key);

    /**
     * Updating or adding settings.
     *
     * @param mixed $key       Unique option key
     * @param mixed $value     Option value
     * @param bool $overwrite Overwrite existing value
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setOption($key, $value, bool $overwrite = true) : bool;

    /**
     * Updating or adding settings.
     *
     * @param array $pair      Key value pair
     * @param bool $overwrite Overwrite existing value
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setOptions(array $pair, bool $overwrite = true) : bool;

    /**
     * Get option by key.
     *
     * @param mixed $key Unique option key
     *
     * @return mixed Option value
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getOption($key);

}
