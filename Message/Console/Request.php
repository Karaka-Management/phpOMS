<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Message\Console
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Message\Console;

use phpOMS\Localization\Localization;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\RequestSource;
use phpOMS\Router\RouteVerb;

/**
 * Request class.
 *
 * @package    phpOMS\Message\Console
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class Request extends RequestAbstract
{
    /**
     * OS type.
     *
     * @var string
     * @since 1.0.0
     */
    private $os = null;

    /**
     * Constructor.
     *
     * @param UriInterface $uri  Uri
     * @param Localization $l11n Localization
     *
     * @since  1.0.0
     */
    public function __construct(UriInterface $uri, Localization $l11n = null)
    {
        $this->header = new Header();

        if ($l11n === null) {
            $l11n = $l11n ?? new Localization();
        }

        $this->header->setL11n($l11n);
    }

    /**
     * Create request hashs of current request
     *
     * The hashes are based on the request path and can be used as unique id.
     *
     * @param int $start Start hash from n-th path element
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function createRequestHashs(int $start = 0) : void
    {
        $this->hash = [];
        $pathArray  = $this->uri->getPathElements();

        foreach ($pathArray as $key => $path) {
            $paths = [];
            for ($i = $start; $i < $key + 1; ++$i) {
                $paths[] = $pathArray[$i];
            }

            $this->hash[] = sha1(implode('', $paths));
        }
    }

    /**
     * Determine request OS.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getOS() : string
    {
        if ($this->os === null) {
            $this->os = PHP_OS;
        }

        return $this->os;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigin() : string
    {
        // todo: maybe return execution path?
        return '127.0.0.1';
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod() : string
    {
        if ($this->method === null) {
            $this->method = RequestMethod::GET;
        }

        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody() : string
    {
        // todo: implement
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteVerb() : int
    {
        switch ($this->getMethod()) {
            case RequestMethod::GET:
                return RouteVerb::GET;
            case RequestMethod::PUT:
                return RouteVerb::PUT;
            case RequestMethod::POST:
                return RouteVerb::SET;
            case RequestMethod::DELETE:
                return RouteVerb::DELETE;
            default:
                throw new \Exception();
        }
    }
}
