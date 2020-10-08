<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Message\Console
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Console;

use phpOMS\Localization\Localization;
use phpOMS\Message\RequestAbstract;
use phpOMS\Uri\Argument;
use phpOMS\Uri\UriInterface;

/**
 * Request class.
 *
 * @package phpOMS\Message\Console
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
    protected UriInterface $uri;

    /**
     * Request method.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $method;

    /**
     * Request hash.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $hash = [];

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
        $this->header = new ConsoleHeader();
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
     * @since 1.0.0
     */
    private function init() : void
    {
        $this->header->getL11n()->setLanguage('en');
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
     * Get request hash.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getHash() : array
    {
        return $this->hash;
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
}
