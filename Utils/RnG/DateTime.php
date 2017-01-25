<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils\RnG;

/**
 * DateTime generator.
 *
 * @category   Framework
 * @package    Utils\RnG
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class DateTime
{

    /**
     * Get a random string.
     *
     * @param string $start Start date
     * @param string $end   End date
     *
     * @return \DateTime
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function generateDateTime(string $start, string $end) : \DateTime
    {
        $startDate = strtotime($start);
        $endDate   = strtotime($end);

        return new \DateTime(date('Y-m-d H:i:s', rand($startDate, $endDate)));
    }
}
