<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Module;

/**
 * Cli module interface.
 *
 * @package phpOMS\Module
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface CliInterface
{
    /**
     * Answer console request.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function callCli() : void;
}
