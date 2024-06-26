<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Message\Socket
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Socket;

use phpOMS\Message\RequestAbstract;
use phpOMS\Router\RouteVerb;

/**
 * Request class.
 *
 * @package phpOMS\Message\Socket
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class SocketRequest extends RequestAbstract
{
    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->header = new SocketHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigin() : string
    {
        return '127.0.0.1';
    }

    /**
     * Get request language
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getRequestLanguage() : string
    {
        return 'en';
    }

    /**
     * Get request locale
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getLocale() : string
    {
        return 'en_US';
    }

    /**
     * {@inheritdoc}
     */
    public function getBody() : string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteVerb() : int
    {
        return RouteVerb::ANY;
    }
}
