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
namespace phpOMS\Log;

use phpOMS\Datatypes\Exception\InvalidEnumValue;
use phpOMS\System\FilePathException;

/**
 * Logging class.
 *
 * @category   Framework
 * @package    phpOMS\Log
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class FileLogger implements LoggerInterface
{
    const MSG_BACKTRACE = '{datetime}; {level}; {ip}; {message}; {backtrace}';
    const MSG_SIMPLE    = '{datetime}; {level}; {ip}; {message};';

    /**
     * Timing array.
     *
     * Potential values are null or an array filled with log timings.
     * This is used in order to profile code sections by ID.
     *
     * @var array[float]
     * @since 1.0.0
     */
    public $timings = [];

    /**
     * Instance.
     *
     * @var \phpOMS\DataStorage\Cache\CacheManager
     * @since 1.0.0
     */
    protected static $instance = null;

    /**
     * The file pointer for the logging.
     *
     * Potential values are null or a valid file pointer
     *
     * @var resource
     * @since 1.0.0
     */
    private $fp = null;

    /**
     * Logging path
     *
     * @var string
     * @since 1.0.0
     */
    private $path = '';

    /**
     * Object constructor.
     *
     * Creates the logging object and overwrites all default values.
     *
     * @param \string $path Path for logging
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __construct(\string $path)
    {
        if(!file_exists($path)) {
            mkdir($path);
        }

        $path = realpath($path);

        if (strpos($path, ROOT_PATH) === false) {
            throw new FilePathException($path);
        }

        if (!file_exists($path)) {
            mkdir($path, 0644, true);
        }

        $this->path = $path . '/' . date('Y-m-d') . '.log';

        if (!file_exists($path)) {
            touch($path);
        }
    }

    /**
     * Returns instance.
     *
     * @param \string $path Logging path
     *
     * @return self
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getInstance(\string $path = '')
    {
        if (self::$instance === null) {
            self::$instance = new self($path);
        }

        return self::$instance;
    }

    /**
     * Object destructor.
     *
     * Closes the logging file
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __destruct()
    {
        if (is_resource($this->fp)) {
            fclose($this->fp);
        }
    }

    /**
     * Protect instance from getting copied from outside.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __clone()
    {
    }

    /**
     * Starts the time measurement.
     *
     * @param \string $id the ID by which this time measurement gets identified
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function startTimeLog($id = '')
    {
        $mtime = explode(' ', microtime());
        $mtime = $mtime[1] + $mtime[0];

        $this->timings[$id] = ['start' => $mtime];
    }

    /**
     * Ends the time measurement.
     *
     * @param \string $id the ID by which this time measurement gets identified
     *
     * @return \int the time measurement
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function endTimeLog($id = '')
    {
        $mtime = explode(' ', microtime());
        $mtime = $mtime[1] + $mtime[0];

        $this->timings[$id]['end']  = $mtime;
        $this->timings[$id]['time'] = $mtime - $this->timings[$id]['start'];

        return $this->timings[$id]['time'];
    }

    /**
     * Sorts timings descending.
     *
     * @param array [float] &$timings the timing array to sort
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function timingSort(&$timings)
    {
        uasort($timings, [$this, 'orderSort']);
    }

    /**
     * Interpolate context
     *
     * @param \string $message
     * @param array   $context
     * @param \string $level
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    private function interpolate(\string $message, array $context = [], \string $level = LogLevel::DEBUG)
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        $backtrace = debug_backtrace();

        if (isset($backtrace[0])) {
            unset($backtrace[0]);
        }

        if (isset($backtrace[1])) {
            unset($backtrace[1]);
        }

        $backtrace = json_encode($backtrace);

        $replace['{backtrace}'] = str_replace(str_replace('\\', '\\\\', ROOT_PATH), '', $backtrace);
        $replace['{datetime}']  = sprintf('%--19s', (new \DateTime('NOW'))->format('Y-m-d H:i:s'));
        $replace['{level}']     = sprintf('%--12s', $level);
        $replace['{ip}']        = sprintf('%--15s', $_SERVER['REMOTE_ADDR']);

        return strtr($message, $replace);
    }

    /**
     * Sorts all timings descending.
     *
     * @param array $a
     * @param array $b
     *
     * @return \bool the comparison
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    private function orderSort($a, $b)
    {
        if ($a['time'] == $b['time']) {
            return 0;
        }

        return ($a['time'] > $b['time']) ? -1 : 1;
    }

    private function write(\string $message)
    {
        $this->fp = fopen($this->path, 'a');
        fwrite($this->fp, $message . "\n");
        fclose($this->fp);
    }

    /**
     * System is unusable.
     *
     * @param \string $message
     * @param array   $context
     *
     * @return null
     */
    public function emergency(\string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::EMERGENCY);
        $this->write($message);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param \string $message
     * @param array   $context
     *
     * @return null
     */
    public function alert(\string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::ALERT);
        $this->write($message);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param \string $message
     * @param array   $context
     *
     * @return null
     */
    public function critical(\string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::CRITICAL);
        $this->write($message);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param \string $message
     * @param array   $context
     *
     * @return null
     */
    public function error(\string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::ERROR);
        $this->write($message);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param \string $message
     * @param array   $context
     *
     * @return null
     */
    public function warning(\string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::WARNING);
        $this->write($message);
    }

    /**
     * Normal but significant events.
     *
     * @param \string $message
     * @param array   $context
     *
     * @return null
     */
    public function notice(\string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::NOTICE);
        $this->write($message);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param \string $message
     * @param array   $context
     *
     * @return null
     */
    public function info(\string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::INFO);
        $this->write($message);
    }

    /**
     * Detailed debug information.
     *
     * @param \string $message
     * @param array   $context
     *
     * @return null
     */
    public function debug(\string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::DEBUG);
        $this->write($message);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param \string $level
     * @param \string $message
     * @param array   $context
     *
     * @return null
     */
    public function log(\string $level, \string $message, array $context = [])
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
     */
    public function countLogs()
    {
        $levels = [];

        if (file_exists($this->path)) {
            $this->fp = fopen($this->path, 'r');
            fseek($this->fp, 0);

            while (($line = fgetcsv($this->fp, 0, ';')) !== false) {
                $line[1] = trim($line[1]);

                if (!isset($levels[$line[1]])) {
                    $levels[$line[1]] = 0;
                }

                $levels[$line[1]]++;
            }

            fseek($this->fp, 0, SEEK_END);
            fclose($this->fp);
        }

        return $levels;
    }

    /**
     * Find cricitcal connections.
     *
     * @param \int $limit Amout of perpetrators
     *
     * @return array
     */
    public function getHighestPerpetrator(\int $limit = 10)
    {
        $connection = [];

        if (file_exists($this->path)) {
            $this->fp = fopen($this->path, 'r');
            fseek($this->fp, 0);

            while (($line = fgetcsv($this->fp, 0, ';')) !== false) {
                $line[2] = trim($line[2]);

                if (!isset($connection[$line[2]])) {
                    $connection[$line[2]] = 0;
                }

                $connection[$line[2]]++;
            }

            fseek($this->fp, 0, SEEK_END);
            fclose($this->fp);
            asort($connection);
        }

        return array_slice($connection, 0, $limit);
    }

    public function get($limit = 25, $offset = 0)
    {
        $logs = [];
        $id   = 0;

        if (file_exists($this->path)) {
            $this->fp = fopen($this->path, 'r');
            fseek($this->fp, 0);

            while (($line = fgetcsv($this->fp, 0, ';')) !== false) {
                $id++;
                $offset--;

                if ($offset > 0) {
                    continue;
                }

                if ($limit <= 0) {
                    $logs = array_reverse($logs, true);
                    array_pop($logs);
                }

                foreach ($line as &$value) {
                    $value = trim($value);
                }

                $logs[$id] = $line;
                $limit--;
            }

            fseek($this->fp, 0, SEEK_END);
            fclose($this->fp);
            asort($logs);
        }

        return $logs;
    }

    public function getByLine(\int $id = 1)
    {
        $log     = [];
        $current = 0;

        if (file_exists($this->path)) {
            $this->fp = fopen($this->path, 'r');
            fseek($this->fp, 0);

            while (($line = fgetcsv($this->fp, 0, ';')) !== false && $current <= $id) {
                $current++;

                if ($current < $id) {
                    continue;
                }

                $log['datetime']  = $line[0] ?? '';
                $log['level']     = $line[1] ?? '';
                $log['ip']        = $line[2] ?? '';
                $log['message']   = $line[3] ?? '';
                $log['backtrace'] = $line[4] ?? '';

                break;
            }

            fseek($this->fp, 0, SEEK_END);
            fclose($this->fp);
        }

        return $log;
    }
}
