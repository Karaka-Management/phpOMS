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
namespace phpOMS\Socket\Packets;

use phpOMS\Socket\CommandManager;
use phpOMS\Socket\Server\ClientManager;

/**
 * Server class.
 *
 * Parsing/serializing arrays to and from php file
 *
 * @category   System
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class PacketManager
{

    /**
     * Command Manager.
     *
     * @var CommandManager
     * @since 1.0.0
     */
    private $commandManager = null;

    /**
     * Client Manager.
     *
     * @var ClientManager
     * @since 1.0.0
     */
    private $clientManager = null;

    /**
     * Constructor.
     *
     * @param CommandManager $cmd  Command Manager
     * @param ClientManager  $user Client Manager
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(CommandManager $cmd, ClientManager $user)
    {
        $this->commandManager = $cmd;
        $this->clientManager  = $user;
    }

    /**
     * Handle package.
     *
     * @param string $data Package data
     * @param mixed   $key  Client Id
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function handle(string $data, $key)
    {
        /*
                if (!empty($data)) {
                    $data = explode(' ', $data);
                    $this->commandManager->trigger($data[0], $key, $data);
                } else {
                    $this->commandManager->trigger('empty', $key, $data);
                }*/
    }
}
