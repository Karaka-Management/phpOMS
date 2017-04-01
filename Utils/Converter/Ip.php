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
declare(strict_types=1);

namespace phpOMS\Utils\Converter;

/**
 * Ip converter.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Ip
{
    /* public */ const IP_TABLE_ITERATIONS = 100;

    private function __construct()
    {
    }

    public static function ip2Float(string $ip) : float
    {
        $split = explode('.', $ip);

        return $split[0] * (256 ** 3) + $split[1] * (256 ** 2) + $split[2] * (256 ** 1) + $split[3];
    }
}