<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests;

use phpOMS\UnhandledHandler;

/**
 * @internal
 */
final class UnhandledHandlerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \phpOMS\UnhandledHandler
     */
    /*
    @bug This is no longer possible with PHPUnit >= 8.0 as it stops the tests
    public function testErrorHandling() : void
    {
        \set_exception_handler(['\phpOMS\UnhandledHandler', 'exceptionHandler']);
        \set_error_handler(['\phpOMS\UnhandledHandler', 'errorHandler']);
        \register_shutdown_function(['\phpOMS\UnhandledHandler', 'shutdownHandler']);

        \trigger_error('', \E_USER_ERROR);

        UnhandledHandler::shutdownHandler();

        self::assertFalse(UnhandledHandler::errorHandler(0, '', '', 0));
    }
    */
}
