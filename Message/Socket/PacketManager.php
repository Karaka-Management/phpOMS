<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Message\Socket
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
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
 * @license OMS License 2.2
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
        $request->header->account = $client->account->id;

        $response = new SocketResponse();

        $this->dispatcher->dispatch(
            $this->router->route($data, null, RouteVerb::ANY, 2, 1, $client->account),
            $request,
            $response
        );
    }
}
