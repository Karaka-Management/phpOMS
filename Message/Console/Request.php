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
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Console;

use phpOMS\Localization\Localization;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\RequestAbstract;
use phpOMS\Router\RouteVerb;
use phpOMS\Uri\Argument;
use phpOMS\Uri\UriInterface;

/**
 * Request class.
 *
 * @package    phpOMS\Message\Console
 * @license    OMS License 1.0
 * @link       https://orange-management.org
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
    public function __construct(UriInterface $uri = null, Localization $l11n = null)
    {
        $this->header = new Header();
        $this->header->setL11n($l11n ?? new Localization());

        $this->uri = $uri ?? new Argument();
        $this->init();
    }

    /**
     * Init request.
     *
     * This is used in order to either initialize the current http request or a batch of GET requests
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function init() : void
    {
        $lang = \explode('_', $_SERVER['LANG'] ?? '');
        $this->header->getL11n()->setLanguage($lang[0] === '' ? 'en' : $lang[0]);
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

            $this->hash[] = \sha1(\implode('', $paths));
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
            $this->os = \strtolower(\PHP_OS);
        }

        return $this->os;
    }

    /**
     * Set OS type
     *
     * @param string $os OS type
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setOS(string $os) : void
    {
        $this->os = $os;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigin() : string
    {
        return '127.0.0.1';
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod() : string
    {
        if ($this->method === null) {
            $temp   = $this->uri->__toString();
            $found  = \stripos($temp, ':');
            $method = $found !== false && $found > 3 && $found < 8 ? \substr($temp, 0, $found) : RequestMethod::GET;

            $this->method = $method === false ? RequestMethod::GET : $method;
        }

        return $this->method;
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
