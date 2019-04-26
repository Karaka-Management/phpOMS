<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Log
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Log;

use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;
use phpOMS\System\File\Local\File;

/**
 * Logging class.
 *
 * @package    phpOMS\Log
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class FileLogger implements LoggerInterface
{
    public const MSG_BACKTRACE = '{datetime}; {level}; {ip}; {message}; {backtrace}';
    public const MSG_FULL      = '{datetime}; {level}; {ip}; {line}; {version}; {os}; {path}; {message}; {file}; {backtrace}';
    public const MSG_SIMPLE    = '{datetime}; {level}; {ip}; {message};';

    /**
     * Timing array.
     *
     * Potential values are null or an array filled with log timings.
     * This is used in order to profile code sections by ID.
     *
     * @var array
     * @since 1.0.0
     */
    private static $timings = [];

    /**
     * Instance.
     *
     * @var FileLogger
     * @since 1.0.0
     */
    protected static $instance = null;

    /**
     * Verbose.
     *
     * @var bool
     * @since 1.0.0
     */
    protected $verbose = false;

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
    private $path = '';

    /**
     * Is the logging file created
     *
     * @var bool
     * @since 1.0.0
     */
    private $created = false;

    /**
     * Object constructor.
     *
     * Creates the logging object and overwrites all default values.
     *
     * @param string $lpath   Path for logging
     * @param bool   $verbose Verbose logging
     *
     * @since  1.0.0
     */
    public function __construct(string $lpath, bool $verbose = false)
    {
        $path          = \realpath(empty($lpath) ? __DIR__ . '/../../' : $lpath);
        $this->verbose = $verbose;

        if (\is_dir($lpath) || \strpos($lpath, '.') === false) {
            $path = \rtrim($lpath, '/') . '/' . \date('Y-m-d') . '.log';
        } else {
            $path = $lpath;
        }

        $this->path = $path;
    }

    /**
     * Create logging file.
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function createFile() : void
    {
        if (!$this->created && !\file_exists($this->path)) {
            File::create($this->path);
            $this->created = true;
        }
    }

    /**
     * Returns instance.
     *
     * @param string $path    Logging path
     * @param bool   $verbose Verbose logging
     *
     * @return FileLogger
     *
     * @since  1.0.0
     */
    public static function getInstance(string $path = '', bool $verbose = false) : self
    {
        if (self::$instance === null) {
            self::$instance = new self($path, $verbose);
        }

        return self::$instance;
    }

    /**
     * Object destructor.
     *
     * Closes the logging file
     *
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public static function startTimeLog($id = '')  : bool
    {
        if (isset(self::$timings[$id])) {
            return false;
        }

        $temp  = \explode(' ', \microtime());
        $mtime = ((float) $temp[1]) + ((float) $temp[0]);

        self::$timings[$id] = ['start' => $mtime];

        return true;
    }

    /**
     * Ends the time measurement.
     *
     * @param string $id the ID by which this time measurement gets identified
     *
     * @return float The time measurement in ms
     *
     * @since  1.0.0
     */
    public static function endTimeLog($id = '') : float
    {
        $temp  = \explode(' ', \microtime());
        $mtime = ((float) $temp[1]) + ((float) $temp[0]);

        self::$timings[$id]['end']  = $mtime;
        self::$timings[$id]['time'] = $mtime - self::$timings[$id]['start'];

        return self::$timings[$id]['time'];
    }

    /**
     * Interpolate context
     *
     * @param string                $message Log schema
     * @param array<string, string> $context Context to log
     * @param string                $level   Log level
     *
     * @return string
     *
     * @since  1.0.0
     */
    private function interpolate(string $message, array $context = [], string $level = LogLevel::DEBUG) : string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        $backtrace = \debug_backtrace();

        // Removing sensitive config data from logging
        foreach ($backtrace as $key => $value) {
            if (isset($value['args'])) {
                unset($backtrace[$key]['args']);
            }
        }

        $backtrace = \json_encode($backtrace);

        $replace['{backtrace}'] = $backtrace;
        $replace['{datetime}']  = \sprintf('%--19s', (new \DateTime('NOW'))->format('Y-m-d H:i:s'));
        $replace['{level}']     = \sprintf('%--12s', $level);
        $replace['{path}']      = $_SERVER['REQUEST_URI'] ?? 'REQUEST_URI';
        $replace['{ip}']        = \sprintf('%--15s', $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        $replace['{version}']   = \sprintf('%--15s', PHP_VERSION);
        $replace['{os}']        = \sprintf('%--15s', PHP_OS);
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
     * @since  1.0.0
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

        if ($this->fp !== false && \flock($this->fp, LOCK_EX)) {
            \fwrite($this->fp, $message . "\n");
            \fflush($this->fp);
            \flock($this->fp, LOCK_UN);
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
     * @since  1.0.0
     */
    public function countLogs()
    {
        $levels = [];

        if (!\file_exists($this->path)) {
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

            $levels[$line[1]]++;
            $line = \fgetcsv($this->fp, 0, ';');
        }

        \fseek($this->fp, 0, SEEK_END);
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
     * @since  1.0.0
     */
    public function getHighestPerpetrator(int $limit = 10) : array
    {
        $connection = [];

        if (!\file_exists($this->path)) {
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

            $connection[$line[2]]++;
            $line = \fgetcsv($this->fp, 0, ';');
        }

        \fseek($this->fp, 0, SEEK_END);
        \fclose($this->fp);
        \asort($connection);

        return \array_slice($connection, 0, $limit);
    }

    /**
     * Get logging messages from file.
     *
     * @param int $limit  Amout of perpetrators
     * @param int $offset Offset
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function get(int $limit = 25, int $offset = 0) : array
    {
        $logs = [];
        $id   = 0;

        if (!\file_exists($this->path)) {
            return $logs;
        }

        $this->fp = \fopen($this->path, 'r');

        if ($this->fp === false) {
            return $logs; // @codeCoverageIgnore
        }

        \fseek($this->fp, 0);

        $line = \fgetcsv($this->fp, 0, ';');
        while ($line !== false && $line !== null) {
            $id++;

            if ($offset > 0) {
                $offset--;
                continue;
            }

            if ($limit <= 0) {
                \reset($logs);
                unset($logs[\key($logs)]);
            }

            foreach ($line as &$value) {
                $value = \trim($value);
            }

            $logs[$id] = $line;
            $limit--;
            \ksort($logs);
            $line = \fgetcsv($this->fp, 0, ';');
        }

        \fseek($this->fp, 0, SEEK_END);
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
     * @since  1.0.0
     */
    public function getByLine(int $id = 1) : array
    {
        $log     = [];
        $current = 0;

        if (!\file_exists($this->path)) {
            return $log;
        }

        $this->fp = \fopen($this->path, 'r');

        if ($this->fp === false) {
            return $log; // @codeCoverageIgnore
        }

        \fseek($this->fp, 0);

        while (($line = \fgetcsv($this->fp, 0, ';')) !== false && $current <= $id) {
            $current++;

            if ($current < $id) {
                continue;
            }

            $log['datetime']  = \trim($line[0] ?? '');
            $log['level']     = \trim($line[1] ?? '');
            $log['ip']        = \trim($line[2] ?? '');
            $log['line']      = \trim($line[3] ?? '');
            $log['version']   = \trim($line[4] ?? '');
            $log['os']        = \trim($line[5] ?? '');
            $log['path']      = \trim($line[6] ?? '');
            $log['message']   = \trim($line[7] ?? '');
            $log['file']      = \trim($line[8] ?? '');
            $log['backtrace'] = \trim($line[9] ?? '');
            break;
        }

        \fseek($this->fp, 0, SEEK_END);
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
     * @since  1.0.0
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
