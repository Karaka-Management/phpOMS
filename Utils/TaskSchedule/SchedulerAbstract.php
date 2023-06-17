<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\TaskSchedule
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

use phpOMS\System\File\PathException;

/**
 * Scheduler abstract.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @codeCoverageIgnore
 */
abstract class SchedulerAbstract
{
    /**
     * Bin path.
     *
     * @var string
     * @since 1.0.0
     */
    private static string $bin = '';

    /**
     * Get git binary.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getBin() : string
    {
        return self::$bin;
    }

    /**
     * Set git binary.
     *
     * @param string $path Git path
     *
     * @return void
     *
     * @throws PathException
     *
     * @since 1.0.0
     */
    public static function setBin(string $path) : void
    {
        if (\realpath($path) === false) {
            throw new PathException($path);
        }

        self::$bin = \realpath($path);
    }

    /**
     * Gues git binary.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function guessBin() : bool
    {
        if (self::$bin !== '') {
            return true;
        }

        $paths = [
            'c:/WINDOWS/system32/schtasks.exe',
            'd:/WINDOWS/system32/schtasks.exe',
            'e:/WINDOWS/system32/schtasks.exe',
            'f:/WINDOWS/system32/schtasks.exe',
            '/usr/bin/crontab',
            '/usr/local/bin/crontab',
            '/usr/local/sbin/crontab',
            '/usr/sbin/crontab',
            '/bin/crontab',
            '/sbin/crontab',
        ];

        foreach ($paths as $path) {
            if (\is_file($path)) {
                self::setBin($path);

                return true;
            }
        }

        return false;
    }

    /**
     * Run command
     *
     * @param string $cmd Command to run
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    protected function run(string $cmd) : string
    {
        $cmd = 'cd ' . \escapeshellarg(\dirname(self::$bin)) . ' && ' . \basename(self::$bin) . ' ' . $cmd;

        $pipes = [];
        $desc  = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $resource = \proc_open($cmd, $desc, $pipes, __DIR__, null);
        if ($resource === false) {
            return '';
        }

        $stdout = \stream_get_contents($pipes[1]);
        $stderr = \stream_get_contents($pipes[2]);

        foreach ($pipes as $pipe) {
            \fclose($pipe);
        }

        $status = \proc_close($resource);

        if ($status === -1 || $stderr !== '') {
            throw new \Exception((string) $stderr);
        }

        return $stdout === false ? '' : \trim($stdout);
    }

    /**
     * Create task
     *
     * @param TaskAbstract $task Task to create
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function create(TaskAbstract $task) : void;

    /**
     * Update task
     *
     * @param TaskAbstract $task Task to update
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function update(TaskAbstract $task) : void;

    /**
     * Delete task by name
     *
     * @param string $name Task name
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function deleteByName(string $name) : void;

    /**
     * Delete task
     *
     * @param TaskAbstract $task Task to delete
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function delete(TaskAbstract $task) : void;

    /**
     * Normalize run result for easier parsing
     *
     * @param string $raw Raw command output
     *
     * @return string Normalized string for parsing
     *
     * @since 1.0.0
     */
    protected function normalize(string $raw) : string
    {
        return \str_replace("\r\n", "\n", $raw);
    }

    /**
     * Get all jobs/tasks by name
     *
     * @param string $name  Name of the job
     * @param bool   $exact Name has to be exact
     *
     * @return array
     *
     * @since 1.0.0
     */
    abstract public function getAllByName(string $name, bool $exact = true) : array;

    /**+
     * Reload the jobs
     *
     * @return void
     * @since 1.0.0
     */
    abstract public function reload() : void;
}
