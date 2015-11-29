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
namespace phpOMS\Module;

/**
 * InfoManager class.
 *
 * Handling the info files for modules
 *
 * @category   Module
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class InfoManager
{

    /**
     * File pointer.
     *
     * @var mixed
     * @since 1.0.0
     */
    private $fp = null;

    /**
     * Module path.
     *
     * @var \string
     * @since 1.0.0
     */
    private static $module_path = __DIR__ . '/../../Modules/';

    /**
     * Object constructor.
     *
     * @param \string $module Module name
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __construct(\string $module)
    {
        if (file_exists(self::$module_path . $module . '/info.json')) {
            $this->fp = fopen(self::$module_path . $module . '/info.json', 'r');
        }
    }

    public function update()
    {
        // TODO: update file (convert to json)
    }

    /**
     * Object destructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __destruct()
    {
        $this->fp->close();
    }
}
