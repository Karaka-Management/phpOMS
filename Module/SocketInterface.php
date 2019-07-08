<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Module
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Module;

/**
 * Socket module interface.
 *
 * @package    phpOMS\Module
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
interface SocketInterface
{

    /**
     * Answer socket request.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function callSock() : void;
}
