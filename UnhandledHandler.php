<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS;

use phpOMS\Log\FileLogger;

/**
 * Default exception and error handler.
 *
 * @category   Web
 * @package    Web
 * @since      1.0.0
 */
final class UnhandledHandler
{

    /**
     * Exception handler.
     *
     * @param mixed $e Exception
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function exceptionHandler($e) /* : void */
    {
        $logger = FileLogger::getInstance(__DIR__ . '/../Logs');
        $logger->critical(FileLogger::MSG_FULL, [
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
        ]);

        echo '<b>My Exception</b> [' . $e->getCode() . '] ' . $e->getMessage() . '<br>'
            . '  Exception on line ' . $e->getLine() . ' in file ' . $e->getFile()
            . ', PHP ' . PHP_VERSION . ' (' . PHP_OS . ')<br>'
            . 'aborting...<br>';
    }

    /**
     * Error handler.
     *
     * @param int    $errno   Error number
     * @param string $errstr  Error message
     * @param string $errfile Error file
     * @param int    $errline Error line
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function errorHandler(int $errno, string $errstr, string $errfile, int $errline) : bool
    {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting
            return false;
        }

        $logger = FileLogger::getInstance(__DIR__ . '/../Logs');
        $logger->error(FileLogger::MSG_FULL, [
            'message' => 'Unhandled error',
            'line'    => $errline,
            'file'    => $errfile,
        ]);

        switch ($errno) {
            case E_USER_ERROR:
                echo '<b>My ERROR</b> [' . $errno . '] ' . $errstr . '<br>';
                break;
            case E_USER_WARNING:
                echo '<b>My WARNING</b> [' . $errno . '] ' . $errstr . '<br>';
                break;
            case E_USER_NOTICE:
                echo '<b>My NOTICE</b> [' . $errno . '] ' . $errstr . '<br>';
                break;
            default:
                echo 'Unknown error type: [' . $errno . '] ' . $errstr . '<br>';
                break;
        }

        echo '<b>My Error</b>  Fatal error on line ' . $errline . ' in file ' . $errfile
            . ', PHP ' . PHP_VERSION . ' (' . PHP_OS . '<br>'
            . 'aborting...<br>';

        error_clear_last();

        return true;
    }

    /**
     * Shutdown handler.
     *
     * @since  1.0.0
     */
    public static function shutdownHandler() /* : void */
    {
        $e = error_get_last();

        if (isset($e)) {
            $logger = FileLogger::getInstance(__DIR__ . '/../Logs');
            $logger->warning(FileLogger::MSG_FULL, [
                'message' => $e['message'],
                'line'    => $e['line'],
                'file'    => $e['file'],
            ]);

            echo '<b>My Error unhandled</b> [' . $e['type'] . '] ' . $e['message'] . '<br>'
                . '  Fatal error on line ' . $e['line'] . ' in file ' . $e['file']
                . ', PHP ' . PHP_VERSION . ' (' . PHP_OS . ')<br>'
                . 'aborting...<br>';
        }
    }
}
