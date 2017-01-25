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

namespace phpOMS\System;

/**
 * System utils
 *
 * @category   Framework
 * @package    phpOMS\System
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class SystemUtils
{

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function __construct()
    {
    }

    /**
     * Get system RAM.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getRAM() : int
    {
        $mem = null;

        if (stristr(PHP_OS, 'WIN')) {
            exec('wmic memorychip get capacity', $mem);
            $mem = array_sum($mem) / 1024;
        } elseif (stristr(PHP_OS, 'LINUX')) {
            $fh  = fopen('/proc/meminfo', 'r');
            $mem = 0;

            while ($line = fgets($fh)) {
                $pieces = [];
                if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                    $mem = $pieces[1] * 1024;
                    break;
                }
            }
            fclose($fh);
        }

        return (int) $mem;
    }

    /**
     * Get RAM usage.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getRAMUsage() : int
    {
        $memusage = 0;

        if (stristr(PHP_OS, 'WIN')) {

        } elseif (stristr(PHP_OS, 'LINUX')) {
            $free     = shell_exec('free');
            $free     = (string) trim($free);
            $free_arr = explode("\n", $free);
            $mem      = explode(" ", $free_arr[1]);
            $mem      = array_filter($mem);
            $mem      = array_merge($mem);
            $memusage = $mem[2] / $mem[1] * 100;
        }

        return (int) $memusage;
    }

    /**
     * Get cpu usage.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getCpuUsage() : int
    {
        $cpuusage = 0;

        if (stristr(PHP_OS, 'WIN')) {
            exec('wmic cpu get LoadPercentage', $cpuusage);
            $cpuusage = $cpuusage[1];
        } elseif (stristr(PHP_OS, 'LINUX')) {
            $cpuusage = \sys_getloadavg()[0];
        }

        return (int) $cpuusage;
    }
}
