<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Uri
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Uri;

/**
 * Console argument class.
 *
 * Considers arguments used in ca CLI as uri
 *
 * @package phpOMS\Uri
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
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
     * @var string[]
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
    public string $scheme = '';

    /**
     * Uri host.
     *
     * @var string
     * @since 1.0.0
     */
    public string $host = '';

    /**
     * Uri port.
     *
     * @var int
     * @since 1.0.0
     */
    public int $port = 0;

    /**
     * Uri user.
     *
     * @var string
     * @since 1.0.0
     */
    public string $user = '';

    /**
     * Uri password.
     *
     * @var string
     * @since 1.0.0
     */
    public string $pass = '';

    /**
     * Uri path.
     *
     * @var string
     * @since 1.0.0
     */
    private string $path = '';

    /**
     * Uri path with offset.
     *
     * @var string
     * @since 1.0.0
     */
    private string $offsetPath = '';

    /**
     * Uri query.
     *
     * @var array<int, string>
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
    public string $fragment = '';

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
     * @since 1.0.0
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

        $uriParts = \explode(' ', $uri);

        // Handle no path information only data
        $uriParts = \stripos($uriParts[0], '-') === 0 ? ['/', $uriParts[0]] : $uriParts;

        $this->path         = \count($uriParts) === 0 ? '' : \array_shift($uriParts);
        $this->pathElements = \explode('/', \ltrim($this->path, '/'));

        $path             = \array_slice($this->pathElements, $this->pathOffset);
        $this->offsetPath = '/' . \implode('/', $path);

        $this->setQuery(\implode(' ', $uriParts));
    }

    /**
     * {@inheritdoc}
     */
    public function setQuery(string $uri) : void
    {
        $result = \explode(' ', $uri);
        if ($result === false) {
            return;
        }

        $this->query       = $result;
        $this->queryString = $uri;
    }

    /**
     * Set fragment from uri.
     *
     * @param string $uri Uri to parse
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function setInternalFragment(string $uri) : void
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

        $path             = \array_slice($this->pathElements, $this->pathOffset);
        $this->offsetPath = '/' . \implode('/', $path);
    }

    /**
     * {@inheritdoc}
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath(string $path) : void
    {
        $this->path         = $path;
        $this->pathElements = \explode('/', \ltrim($this->path, '/'));

        $path             = \array_slice($this->pathElements, $this->pathOffset);
        $this->offsetPath = '/' . \implode('/', $path);
    }

    /**
     * Get path offset.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getPathOffset() : int
    {
        return $this->pathOffset;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute(bool $ignoreOffset = false) : string
    {
        $path = $ignoreOffset ? $this->path : $this->offsetPath;

        $query = $this->getQuery();
        return $path . (!empty($query) ? ' ' . $this->getQuery() : '');
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(string $key = null) : string
    {
        if ($key !== null) {
            $key = (int) \strtolower($key);

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
    public function getUserInfo() : string
    {
        return '';
    }
}
