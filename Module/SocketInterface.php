<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Module;

/**
 * Socket module interface.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
interface SocketInterface
{
    /**
     * Answer socket request.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function callSock() : void;
}
