<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\System
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\System;

/**
 * System utils
 *
 * @package phpOMS\System
 * @license OMS License 1.0
 * @link    https://karaka.app
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
            $memUsage = ((float) ($mem[2] ?? 0.0)) / ((float) ($mem[1] ?? 1.0)) * 100;
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
            $loadavg = \sys_getloadavg();

            if ($loadavg === false) {
                return -1;
            }

            $cpuUsage = $loadavg[0] * 100 / \exec('nproc');
        }

        return (int) $cpuUsage;
    }

    /**
     * Get the server hostname.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getHostname() : string
    {
        if (isset($_SERVER['SERVER_NAME'])) {
            return $_SERVER['SERVER_NAME'];
        } elseif (($result = \gethostname()) !== false) {
            return $result;
        } elseif (\php_uname('n') !== false) {
            return \php_uname('n');
        }

        return 'localhost.localdomain';
    }

    /**
     * Execute a command
     *
     * @param string $executable Path or name of the executable
     * @param string $cmd        Command to execute
     * @param bool   $async      Execute async
     */
    public static function runProc(string $executable, string $cmd, bool $async = false) : array
    {
        if (\strtolower((string) \substr(\PHP_OS, 0, 3)) === 'win') {
            $cmd = 'cd ' . \escapeshellarg(\dirname($executable))
                . ' && ' . \basename($executable)
                . ' '
                . $cmd;

            if ($async) {
                $cmd .= ' > nul 2>&1 &';
            }
        } else {
            $cmd = \escapeshellarg($executable)
                . ' '
                . $cmd;

            if ($async) {
                $cmd .= ' > /dev/null 2>&1 &';
            }
        }

        $pipes = [];
        $desc  = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $resource = \proc_open($cmd, $desc, $pipes, null, null);

        if ($resource === false) {
            throw new \Exception();
        }

        $stdout = '';
        $stderr = '';

        if ($async) {
            \stream_set_blocking($pipes[1], false);
            \stream_set_blocking($pipes[2], false);
        } else {
            $stdout = \stream_get_contents($pipes[1]);
            $stderr = \stream_get_contents($pipes[2]);
        }

        foreach ($pipes as $pipe) {
            \fclose($pipe);
        }

        $status = \proc_close($resource);

        if ($status == -1) {
            throw new \Exception((string) $stderr);
        }

        $lines = \trim(
            $stdout === false
                ? ''
                : (empty($stdout)
                    ? ($stderr === false ? '' : $stderr)
                    : $stdout)
            );

        $lineArray = \preg_split('/\r\n|\n|\r/', $lines);
        $lines     = [];

        if ($lineArray === false) {
            return $lines;
        }

        foreach ($lineArray as $line) {
            $temp = \preg_replace('/\s+/', ' ', \trim($line, ' '));

            if (!empty($temp)) {
                $lines[] = $temp;
            }
        }

        return $lines;
    }
}
