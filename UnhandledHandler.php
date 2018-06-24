<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS;

use phpOMS\Log\FileLogger;

/**
 * Default exception and error handler.
 *
 * @package    Web
 * @since      1.0.0
 */
final class UnhandledHandler
{

    /**
     * Exception handler.
     *
     * @param \Throwable $e Exception
     *
     * @return void
     *
     * @since  1.0.0
     * @codeCoverageIgnore
     */
    public static function exceptionHandler(\Throwable $e) : void
    {
        $logger = FileLogger::getInstance(__DIR__ . '/../Logs');
        $logger->critical(FileLogger::MSG_FULL, [
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
        ]);

        $r=   2;
        var_dump('test');
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
        $logger = FileLogger::getInstance(__DIR__ . '/../Logs');

        if (!(error_reporting() & $errno)) {
            $logger->error(FileLogger::MSG_FULL, [
                'message' => 'Undefined error',
                'line'    => $errline,
                'file'    => $errfile,
            ]);

            error_clear_last();

            return false;
        }


        $logger->error(FileLogger::MSG_FULL, [
            'message' => 'Unhandled error',
            'line'    => $errline,
            'file'    => $errfile,
        ]);

        error_clear_last();

        return true;
    }

    /**
     * Shutdown handler.
     *
     * @return void
     *
     * @since  1.0.0
     * @codeCoverageIgnore
     */
    public static function shutdownHandler() : void
    {
        $e = error_get_last();

        if (isset($e)) {
            $logger = FileLogger::getInstance(__DIR__ . '/../Logs');
            $logger->warning(FileLogger::MSG_FULL, [
                'message' => $e['message'],
                'line'    => $e['line'],
                'file'    => $e['file'],
            ]);
        }
    }
}
