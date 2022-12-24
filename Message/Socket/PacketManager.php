<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Socket
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Socket;

use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Router\RouteVerb;
use phpOMS\Router\SocketRouter;

/**
 * Server class.
 *
 * Parsing/serializing arrays to and from php file
 *
 * @package phpOMS\Message\Socket
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class PacketManager
{
    /**
     * Socket router.
     *
     * @var SocketRouter
     * @since 1.0.0
     */
    private SocketRouter $router;

    /**
     * Dispatcher.
     *
     * @var Dispatcher
     * @since 1.0.0
     */
    private Dispatcher $dispatcher;

    /**
     * Constructor.
     *
     * @param SocketRouter $router     Socket router
     * @param Dispatcher   $dispatcher Dispatcher
     *
     * @since 1.0.0
     */
    public function __construct(SocketRouter $router, Dispatcher $dispatcher)
    {
        $this->router     = $router;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle package.
     *
     * @param string $data Package data
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function handle(string $data, $client) : void
    {
        $request                  = new SocketRequest();
        $request->header->account = $client->getAccount()->getId();

        $response = new SocketResponse();

        $this->dispatcher->dispatch(
            $this->router->route($data, null, RouteVerb::ANY, 'Socket', 1, $client->getAccount()),
            $request,
            $response
        );
    }
}
