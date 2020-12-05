<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\System
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\System;

/**
 * System utils
 *
 * @package phpOMS\System
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class SystemUtils
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Get system RAM.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getRAM() : int
    {
        $mem = 0;

        if (\stristr(\PHP_OS, 'WIN')) {
            $memArr = [];
            \exec('wmic memorychip get capacity', $memArr);

            $mem = \array_sum($memArr) / 1024;
        } elseif (\stristr(\PHP_OS, 'LINUX')) {
            $fh = \fopen('/proc/meminfo', 'r');

            if ($fh === false) {
                return $mem; // @codeCoverageIgnore
            }

            while ($line = \fgets($fh)) {
                $pieces = [];
                if (\preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                    $mem = (int) ($pieces[1] ?? 0) * 1024;
                    break;
                }
            }

            \fclose($fh);
        }

        return (int) $mem;
    }

    /**
     * Get RAM usage.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getRAMUsage() : int
    {
        $memUsage = 0;

        if (\stristr(\PHP_OS, 'LINUX')) {
            $free = \shell_exec('free');

            if ($free === null || $free === false) {
                return $memUsage; // @codeCoverageIgnore
            }

            $free     = \trim($free);
            $freeArr  = \explode("\n", $free);
            $mem      = \explode(' ', $freeArr[1]);
            $mem      = \array_values(\array_filter($mem));
            $memUsage = $mem[2] / $mem[1] * 100;
        }

        return (int) $memUsage;
    }

    /**
     * Get cpu usage.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getCpuUsage() : int
    {
        $cpuUsage = 0;

        if (\stristr(\PHP_OS, 'WIN') !== false) {
            $cpuUsage = null;
            \exec('wmic cpu get LoadPercentage', $cpuUsage);
            $cpuUsage = $cpuUsage[1];
        } elseif (\stristr(\PHP_OS, 'LINUX') !== false) {
            $loadavg  = \sys_getloadavg();

            if ($loadavg === false) {
                return -1;
            }

            $cpuUsage = $loadavg[0] * 100 / \exec('nproc');
        }

        return (int) $cpuUsage;
    }
}
