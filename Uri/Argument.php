<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Uri
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Uri;

use phpOMS\Utils\StringUtils;

/**
 * Console argument class.
 *
 * Considers arguments used in ca CLI as uri
 *
 * @package    phpOMS\Uri
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class Argument implements UriInterface
{

    /**
     * Root path.
     *
     * @var string
     * @since 1.0.0
     */
    private string $rootPath = '/';

    /**
     * Path offset.
     *
     * @var int
     * @since 1.0.0
     */
    private int $pathOffset = 0;

    /**
     * Path elements.
     *
     * @var array
     * @since 1.0.0
     */
    private array $pathElements = [];

    /**
     * Uri.
     *
     * @var string
     * @since 1.0.0
     */
    private string $uri = '';

    /**
     * Uri scheme.
     *
     * @var string
     * @since 1.0.0
     */
    private string $scheme = '';

    /**
     * Uri host.
     *
     * @var string
     * @since 1.0.0
     */
    private string $host = '';

    /**
     * Uri port.
     *
     * @var int
     * @since 1.0.0
     */
    private int $port = 0;

    /**
     * Uri user.
     *
     * @var string
     * @since 1.0.0
     */
    private string $user = '';

    /**
     * Uri password.
     *
     * @var string
     * @since 1.0.0
     */
    private string $pass = '';

    /**
     * Uri path.
     *
     * @var string
     * @since 1.0.0
     */
    private string $path = '';

    /**
     * Uri query.
     *
     * @var array
     * @since 1.0.0
     */
    private array $query = [];

    /**
     * Uri query.
     *
     * @var string
     * @since 1.0.0
     */
    private string $queryString = '';

    /**
     * Uri fragment.
     *
     * @var string
     * @since 1.0.0
     */
    private string $fragment = '';

    /**
     * Uri base.
     *
     * @var string
     * @since 1.0.0
     */
    private string $base = '';

    /**
     * Constructor.
     *
     * @param string $uri Uri
     *
     * @since  1.0.0
     */
    public function __construct(string $uri = '')
    {
        $this->set($uri);
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $uri) : void
    {
        $this->uri = $uri;

        $this->setPath($uri);
        $this->setQuery($uri);
        $this->setFragment($uri);
    }

    /**
     * Set path from uri.
     *
     * @param string $uri Uri to parse
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function setPath(string $uri) : void
    {
        $start = \stripos($uri, ':');

        if ($start === false) {
            return;
        }

        $end = \stripos($uri, ' ', $start + 1);

        if ($end === false) {
            $end = \strlen($uri);
        }

        $path       = $start < 8 ? \substr($uri, $start + 1, $end - $start - 1) : $uri;
        $this->path = $path === false ? '' : \ltrim($path, ':');

        if (StringUtils::endsWith($this->path, '.php')) {
            $path = \substr($this->path, 0, -4);

            if ($path === false) {
                throw new \Exception(); // @codeCoverageIgnore
            }

            $this->path = $path;
        }

        $this->pathElements = \explode('/', \ltrim($this->path, '/'));
    }

    /**
     * Set query from uri.
     *
     * @param string $uri Uri to parse
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function setQuery(string $uri) : void
    {
        $result = \preg_match_all('/\?([a-zA-Z0-9]*)(=)([a-zA-Z0-9]*)/', $uri, $matches);

        if ($result === false || empty($matches)) {
            return;
        }

        foreach ($matches[1] as $key => $value) {
            $this->query[$value] = $matches[3][$key];
            $this->queryString  .= ' ?' . $value . '=' . $matches[3][$key];
        }

        $this->queryString = \ltrim($this->queryString);
    }

    /**
     * Set fragment from uri.
     *
     * @param string $uri Uri to parse
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function setFragment(string $uri) : void
    {
        $result = \preg_match('/#([a-zA-Z0-9]*)/', $uri, $matches);

        if ($result === 1) {
            $this->fragment = $matches[1] ?? '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function isValid(string $uri) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getRootPath() : string
    {
        return $this->rootPath;
    }

    /**
     * {@inheritdoc}
     */
    public function setRootPath(string $root) : void
    {
        $this->rootPath = $root;
    }

    /**
     * {@inheritdoc}
     */
    public function setPathOffset(int $offset = 0) : void
    {
        $this->pathOffset = $offset;
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme() : string
    {
        return $this->scheme;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost() : string
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    public function getPort() : int
    {
        return $this->port;
    }

    /**
     * {@inheritdoc}
     */
    public function getPass() : string
    {
        return $this->pass;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Get path offset.
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function getPathOffset() : int
    {
        return \substr_count($this->rootPath, '/') - 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute() : string
    {
        $query = $this->getQuery();
        return $this->path . (!empty($query) ? ' ' . $this->getQuery() : '');
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(string $key = null)  : string
    {
        if ($key !== null) {
            $key = \strtolower($key);

            return $this->query[$key] ?? '';
        }

        return $this->queryString;
    }

    /**
     * {@inheritdoc}
     */
    public function getPathElement(int $pos = 0) : string
    {
        return $this->pathElements[$pos + $this->pathOffset] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getPathElements() : array
    {
        return $this->pathElements;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryArray() : array
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function getFragment() : string
    {
        return $this->fragment;
    }

    /**
     * {@inheritdoc}
     */
    public function getBase() : string
    {
        return $this->base;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthority() : string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getUser() : string
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo() : string
    {
        return '';
    }
}
