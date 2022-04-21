<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Console
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Console;

use phpOMS\Localization\Localization;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\RequestAbstract;
use phpOMS\Router\RouteVerb;
use phpOMS\Uri\Argument;
use phpOMS\Uri\UriInterface;
use phpOMS\Utils\ArrayUtils;

/**
 * Request class.
 *
 * @package phpOMS\Message\Console
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class ConsoleRequest extends RequestAbstract
{
    /**
     * Uri.
     *
     * @var UriInterface
     * @since 1.0.0
     */
    public UriInterface $uri;

    /**
     * Request method.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $method = RequestMethod::GET;

    /**
     * OS type.
     *
     * @var string
     * @since 1.0.0
     */
    private string $os;

    /**
     * Constructor.
     *
     * @param UriInterface $uri  Uri
     * @param Localization $l11n Localization
     *
     * @since 1.0.0
     */
    public function __construct(UriInterface $uri = null, Localization $l11n = null)
    {
        $this->header       = new ConsoleHeader();
        $this->header->l11n = $l11n ?? new Localization();

        $this->uri = $uri ?? new Argument();
        $this->init();
    }

    /**
     * Get data.
     *
     * @param string $key  Data key
     * @param string $type Return type
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getData(string $key = null, string $type = null) : mixed
    {
        if ($key === null) {
            return $this->data;
        }

        $key = '-' . \mb_strtolower($key);

        if ($type === null) {
            /** @var string[] $this->data */
            return ArrayUtils::getArg($key, $this->data);
        }

        switch ($type) {
            case 'int':
                return (int) ArrayUtils::getArg($key, $this->data);
            case 'string':
                return (string) ArrayUtils::getArg($key, $this->data);
            case 'float':
                return (float) ArrayUtils::getArg($key, $this->data);
            case 'bool':
                return (bool) ArrayUtils::getArg($key, $this->data);
            default:
                return ArrayUtils::getArg($key, $this->data);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasData(string $key) : bool
    {
        $key = '-' . \mb_strtolower($key);

        return ArrayUtils::hasArg($key, $this->data) > -1;
    }

    /**
     * Set request data.
     *
     * @param string $key       Data key
     * @param mixed  $value     Value
     * @param bool   $overwrite Overwrite data
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function setData(string $key, mixed $value, bool $overwrite = false) : bool
    {
        $key = '-' . \mb_strtolower($key);

        $pos = -1;
        if ($overwrite || ($pos = ArrayUtils::hasArg($key, $this->data)) !== -1) {
            if ($pos === -1) {
                $this->data[] = $key;
                $this->data[] = $value;
            } else {
                $this->data[$pos]     = $key;
                $this->data[$pos + 1] = $value;
            }

            $this->uri->setQuery(\implode(' ', $this->data));

            return true;
        }

        return false;
    }

    /**
     * Init request.
     *
     * This is used in order to either initialize the current http request or a batch of GET requests
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function init() : void
    {
        $this->header->l11n->setLanguage('en');
        $this->data = $this->uri->getQueryArray();
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
     * @since 1.0.0
     */
    public function createRequestHashs(int $start = 0) : void
    {
        $this->hash = [\sha1('')];
        $pathArray  = $this->uri->getPathElements();
        $pathLength = \count($pathArray);

        for ($i = $start; $i < $pathLength; ++$i) {
            if ($pathArray[$i] === '') {
                continue;
            }

            $paths = [];
            for ($j = $start; $j <= $i; ++$j) {
                $paths[] = $pathArray[$j];
            }

            $this->hash[] = \sha1(\implode('', $paths));
        }
    }

    /**
     * Set request method.
     *
     * @param string $method Request method
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setMethod(string $method) : void
    {
        $this->method = $method;
    }

    /**
     * Get request method.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Determine request OS.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getOS() : string
    {
        if (!isset($this->os)) {
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
     * @since 1.0.0
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
    public function getBody() : string
    {
        return '';
    }

    /**
     * Get route verb.
     *
     * @return int
     *
     * @since 1.0.0
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
