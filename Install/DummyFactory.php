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
namespace phpOMS\Install;

/**
 * Dummy data factory.
 *
 * Used in order to install dummy data of modules
 *
 * @category   Install
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class DummyFactory
{

    /**
     * Generate dummy data.
     *
     * @param \phpOMS\DataStorage\Database\Pool $db     Database instance
     * @param \string                           $module Module name (= directory name)
     * @param \int                              $amount Amount of dummy data
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function generate($db, $module, $amount = 9997)
    {
        if (file_exists(__DIR__ . '/../../Modules/' . $module . '/Admin/Dummy.class.php')) {
            /** @var \phpOMS\Install\DummyInterface $class */
            $class = '\\Modules\\' . $module . '\\Admin\\Dummy';
            $class::generate($db, $amount);
        }
    }

}
