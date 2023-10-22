<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Log
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Log;

use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;
use phpOMS\System\File\Local\File;

/**
 * Logging class.
 *
 * @package phpOMS\Log
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class FileLogger implements LoggerInterface
{
    public const MSG_BACKTRACE = '{datetime}; {level}; {ip}; {message}; {backtrace}';

    public const MSG_FULL = '{datetime}; {level}; {ip}; {line}; {version}; {os}; {path}; {message}; {file}; {backtrace}';

    public const MSG_SIMPLE = '{datetime}; {level}; {ip}; {message};';

    /**
     * Timing array.
     *
     * Potential values are null or an array filled with log timings.
     * This is used in order to profile code sections by ID.
     *
     * @var array<string, array{start:float, end:float, time:float}>
     * @since 1.0.0
     */
    private static array $timings = [];

    /**
     * Instance.
     *
     * @var FileLogger
     * @since 1.0.0
     */
    protected static FileLogger $instance;

    /**
     * Verbose.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $verbose = false;

    /**
     * The file pointer for the logging.
     *
     * Potential values are null or a valid file pointer
     *
     * @var false|resource
     * @since 1.0.0
     */
    private $fp = false;

    /**
     * Logging path
     *
     * @var string
     * @since 1.0.0
     */
    private string $path;

    /**
     * Is the logging file created
     *
     * @var bool
     * @since 1.0.0
     */
    private bool $created = false;

    /**
     * Object constructor.
     *
     * Creates the logging object and overwrites all default values.
     *
     * @param string $lpath   Path for logging
     * @param bool   $verbose Verbose logging
     *
     * @since 1.0.0
     */
    public function __construct(string $lpath = '', bool $verbose = false)
    {
        $path          = \realpath(empty($lpath) ? __DIR__ . '/../../Logs/' : $lpath);
        $this->verbose = $verbose;

        $this->path = \is_dir($lpath) || \strpos($lpath, '.') === false
            ? \rtrim($path !== false ? $path : $lpath, '/') . '/' . \date('Y-m-d') . '.log'
            : $lpath;
    }

    /**
     * Create logging file.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function createFile() : void
    {
        if (!$this->created && !\is_file($this->path)) {
            File::create($this->path);
            $this->created = true;
        }
    }

    /**
     * Returns instance.
     *
     * @param string $lpath   Logging path
     * @param bool   $verbose Verbose logging
     *
     * @return FileLogger
     *
     * @since 1.0.0
     */
    public static function getInstance(string $lpath = '', bool $verbose = false) : self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($lpath, $verbose);
        }

        return self::$instance;
    }

    /**
     * Object destructor.
     *
     * Closes the logging file
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __destruct()
    {
        if (\is_resource($this->fp)) {
            \fclose($this->fp);
        }
    }

    /**
     * Protect instance from getting copied from outside.
     *
     * @return void
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __clone()
    {
    }

    /**
     * Starts the time measurement.
     *
     * @param string $id the ID by which this time measurement gets identified
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function startTimeLog(string $id = '') : bool
    {
        self::$timings[$id] = ['start' => \microtime(true), 'end' => 0.0, 'time' => 0.0];

        return true;
    }

    /**
     * Ends the time measurement.
     *
     * @param string $id the ID by which this time measurement gets identified
     *
     * @return float The time measurement in seconds
     *
     * @since 1.0.0
     */
    public static function endTimeLog(string $id = '') : float
    {
        $mtime = \microtime(true);

        self::$timings[$id]['end']  = $mtime;
        self::$timings[$id]['time'] = $mtime - self::$timings[$id]['start'];

        return self::$timings[$id]['time'];
    }

    /**
     * Interpolate context
     *
     * @param string                                    $message Log schema
     * @param array<string, null|int|bool|float|string> $context Context to log
     * @param string                                    $level   Log level
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function interpolate(string $message, array $context = [], string $level = LogLevel::DEBUG) : string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = \str_replace(["\r\n", "\r", "\n"], ' ', (string) $val);
        }

        $backtrace = \debug_backtrace();

        // Removing sensitive config data from logging
        foreach ($backtrace as $key => $value) {
            if (isset($value['args'])) {
                unset($backtrace[$key]['args']);
            }
        }

        $encodedBacktrace = \json_encode($backtrace);
        if (!\is_string($encodedBacktrace)) {
            $encodedBacktrace = '';
        }

        $backtrace = \str_replace(["\r\n", "\r", "\n"], ' ', $encodedBacktrace);

        $replace['{backtrace}'] = $backtrace;
        $replace['{datetime}']  = \sprintf('%--19s', (new \DateTimeImmutable('NOW'))->format('Y-m-d H:i:s'));
        $replace['{level}']     = \sprintf('%--12s', $level);
        $replace['{path}']      = $_SERVER['REQUEST_URI'] ?? 'REQUEST_URI';
        $replace['{ip}']        = \sprintf('%--15s', $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        $replace['{version}']   = \sprintf('%--15s', \PHP_VERSION);
        $replace['{os}']        = \sprintf('%--15s', \PHP_OS);
        $replace['{line}']      = \sprintf('%--15s', $context['line'] ?? '?');

        return \strtr($message, $replace);
    }

    /**
     * Write to file.
     *
     * @param string $message Log message
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function write(string $message) : void
    {
        if ($this->verbose) {
            echo $message, "\n";
        }

        $this->createFile();
        if (!\is_writable($this->path)) {
            return; // @codeCoverageIgnore
        }

        $this->fp = \fopen($this->path, 'a');

        if ($this->fp !== false && \flock($this->fp, \LOCK_EX)) {
            \fwrite($this->fp, $message . "\n");
            \fflush($this->fp);
            \flock($this->fp, \LOCK_UN);
            \fclose($this->fp);
            $this->fp = false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function emergency(string $message, array $context = []) : void
    {
        $message = $this->interpolate($message, $context, LogLevel::EMERGENCY);
        $this->write($message);
    }

    /**
     * {@inheritdoc}
     */
    public function alert(string $message, array $context = []) : void
    {
        $message = $this->interpolate($message, $context, LogLevel::ALERT);
        $this->write($message);
    }

    /**
     * {@inheritdoc}
     */
    public function critical(string $message, array $context = []) : void
    {
        $message = $this->interpolate($message, $context, LogLevel::CRITICAL);
        $this->write($message);
    }

    /**
     * {@inheritdoc}
     */
    public function error(string $message, array $context = []) : void
    {
        $message = $this->interpolate($message, $context, LogLevel::ERROR);
        $this->write($message);
    }

    /**
     * {@inheritdoc}
     */
    public function warning(string $message, array $context = []) : void
    {
        $message = $this->interpolate($message, $context, LogLevel::WARNING);
        $this->write($message);
    }

    /**
     * {@inheritdoc}
     */
    public function notice(string $message, array $context = []) : void
    {
        $message = $this->interpolate($message, $context, LogLevel::NOTICE);
        $this->write($message);
    }

    /**
     * {@inheritdoc}
     */
    public function info(string $message, array $context = []) : void
    {
        $message = $this->interpolate($message, $context, LogLevel::INFO);
        $this->write($message);
    }

    /**
     * {@inheritdoc}
     */
    public function debug(string $message, array $context = []) : void
    {
        $message = $this->interpolate($message, $context, LogLevel::DEBUG);
        $this->write($message);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidEnumValue
     */
    public function log(string $level, string $message, array $context = []) : void
    {
        if (!LogLevel::isValidValue($level)) {
            throw new InvalidEnumValue($level);
        }

        $message = $this->interpolate($message, $context, $level);
        $this->write($message);
    }

    /**
     * Analyse logging file.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function countLogs() : array
    {
        $levels = [];

        if (!\is_file($this->path)) {
            return $levels;
        }

        $this->fp = \fopen($this->path, 'r');

        if ($this->fp === false) {
            return $levels; // @codeCoverageIgnore
        }

        \fseek($this->fp, 0);
        $line = \fgetcsv($this->fp, 0, ';');

        while ($line !== false && $line !== null) {
            if (\count($line) < 2) {
                continue; // @codeCoverageIgnore
            }

            $line[1] = \trim($line[1]);

            if (!isset($levels[$line[1]])) {
                $levels[$line[1]] = 0;
            }

            ++$levels[$line[1]];
            $line = \fgetcsv($this->fp, 0, ';');
        }

        \fseek($this->fp, 0, \SEEK_END);
        \fclose($this->fp);

        return $levels;
    }

    /**
     * Find cricitcal connections.
     *
     * @param int $limit Amout of perpetrators
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getHighestPerpetrator(int $limit = 10) : array
    {
        $connection = [];

        if (!\is_file($this->path)) {
            return $connection;
        }

        $this->fp = \fopen($this->path, 'r');

        if ($this->fp === false) {
            return $connection; // @codeCoverageIgnore
        }

        \fseek($this->fp, 0);
        $line = \fgetcsv($this->fp, 0, ';');

        while ($line !== false && $line !== null) {
            if (\count($line) < 3) {
                continue; // @codeCoverageIgnore
            }

            $line[2] = \trim($line[2]);

            if (!isset($connection[$line[2]])) {
                $connection[$line[2]] = 0;
            }

            ++$connection[$line[2]];
            $line = \fgetcsv($this->fp, 0, ';');
        }

        \fseek($this->fp, 0, \SEEK_END);
        \fclose($this->fp);
        \asort($connection);

        return \array_slice($connection, 0, $limit);
    }

    /**
     * Get logging messages from file.
     *
     * @param int $limit  Amout of logs
     * @param int $offset Offset
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function get(int $limit = 25, int $offset = 0) : array
    {
        $logs = [];
        $id   = 0;

        if (!\is_file($this->path)) {
            return $logs;
        }

        $this->fp = \fopen($this->path, 'r');

        if ($this->fp === false) {
            return $logs; // @codeCoverageIgnore
        }

        \fseek($this->fp, 0);

        $line = \fgetcsv($this->fp, 0, ';');
        while ($line !== false && $line !== null) {
            if ($limit < 1) {
                break;
            }

            ++$id;

            if ($offset > 0) {
                $line = \fgetcsv($this->fp, 0, ';');

                --$offset;
                continue;
            }

            foreach ($line as &$value) {
                $value = \trim($value);
            }

            $logs[$id] = $line;

            --$limit;

            $line = \fgetcsv($this->fp, 0, ';');
        }

        \fseek($this->fp, 0, \SEEK_END);
        \fclose($this->fp);

        return $logs;
    }

    /**
     * Get single logging message from file.
     *
     * @param int $id Id/Line number of the logging message
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getByLine(int $id = 1) : array
    {
        $log     = [];
        $current = 0;

        if (!\is_file($this->path)) {
            return $log;
        }

        $this->fp = \fopen($this->path, 'r');

        if ($this->fp === false) {
            return $log; // @codeCoverageIgnore
        }

        \fseek($this->fp, 0);

        while (($line = \fgetcsv($this->fp, 0, ';')) !== false && $current <= $id) {
            ++$current;

            if ($current < $id || $line === null) {
                continue;
            }

            foreach ($line as $value) {
                $log[] = \trim($value);
            }

            break;
        }

        \fseek($this->fp, 0, \SEEK_END);
        \fclose($this->fp);

        return $log;
    }

    /**
     * Create console log.
     *
     * @param string                $message Log message
     * @param bool                  $verbose Is verbose
     * @param array<string, string> $context Context
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function console(string $message, bool $verbose = true, array $context = []) : void
    {
        if (empty($context)) {
            $message = \date('[Y-m-d H:i:s] ') . $message . "\r\n";
        }

        if ($verbose) {
            echo $this->interpolate($message, $context);
        } else {
            $this->info($message, $context);
        }
    }
}
