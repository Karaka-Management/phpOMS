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
namespace phpOMS\Utils\RnG;

/**
 * String generator.
 *
 * @category   Framework
 * @package    Utils\RnG
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class StringUtils
{

    /**
     * Get a random string.
     *
     * @param \int    $min     Min. length
     * @param \int    $max     Max. length
     * @param \string $charset Allowed characters
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function generateString($min = 10, $max = 10, $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $length           = rand($min, $max);
        $charactersLength = strlen($charset);
        $randomString     = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $charset[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
