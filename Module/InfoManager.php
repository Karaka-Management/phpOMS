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
use phpOMS\System\FilePathException;
use phpOMS\Validation\Validator;

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
     * @var string
     * @since 1.0.0
     */
    const MODULE_PATH = __DIR__ . '/../../Modules/';

    /**
     * Object constructor.
     *
     * @param string $module Module name
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __construct(string $module)
    {
        if (($path = realpath($oldPath = self::MODULE_PATH . $module . '/info.json')) === false || Validator::startsWith($path, self::MODULE_PATH)) {
            throw new FilePathException($oldPath);
        }

        $this->fp = fopen($oldPath, 'r');
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
