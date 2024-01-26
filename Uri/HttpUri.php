<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Uri
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Uri;

use phpOMS\Utils\StringUtils;

/**
 * HTTP Uri.
 *
 * Uri used for http requests (incoming & outgoing)
 *
 * @package phpOMS\Uri
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class HttpUri implements UriInterface
{
    /**
     * Root path.
     *
     * @var string
     * @since 1.0.0
     */
    private string $rootPath = '';

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
    private array $pathElements;

    /**
     * Uri.
     *
     * @var string
     * @since 1.0.0
     */
    public string $uri;

    /**
     * Uri scheme.
     *
     * @var string
     * @since 1.0.0
     */
    public string $scheme;

    /**
     * Uri host.
     *
     * @var string
     * @since 1.0.0
     */
    public string $host;

    /**
     * Uri port.
     *
     * @var int
     * @since 1.0.0
     */
    public int $port;

    /**
     * Uri user.
     *
     * @var string
     * @since 1.0.0
     */
    public string $user;

    /**
     * Uri password.
     *
     * @var string
     * @since 1.0.0
     */
    public string $pass;

    /**
     * Uri path.
     *
     * @var string
     * @since 1.0.0
     */
    public string $path;

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
     * @var array<string, string>
     * @since 1.0.0
     */
    private array $query = [];

    /**
     * Uri query.
     *
     * @var string
     * @since 1.0.0
     */
    private string $queryString;

    /**
     * Uri fragment.
     *
     * @var string
     * @since 1.0.0
     */
    public string $fragment;

    /**
     * Uri fragments.
     *
     * @var array
     * @since 1.0.0
     */
    public array $fragments = [];

    /**
     * Uri base.
     *
     * @var string
     * @since 1.0.0
     */
    private string $base;

    /**
     * Constructor.
     *
     * @param string $uri Root path for subdirectory
     *
     * @since 1.0.0
     */
    public function __construct(string $uri)
    {
        $this->set($uri);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function set(string $uri) : void
    {
        $this->uri = $uri;
        $url       = \parse_url($this->uri);

        if ($url === false) {
            $this->scheme       = '';
            $this->host         = '';
            $this->port         = 80;
            $this->user         = '';
            $this->pass         = '';
            $this->path         = '';
            $this->queryString  = '';
            $this->query        = [];
            $this->pathElements = [];
            $this->fragment     = '';
            $this->base         = '';

            return;
        }

        $this->scheme = $url['scheme'] ?? '';
        $this->host   = $url['host'] ?? '';
        $this->port   = $url['port'] ?? 80;
        $this->user   = $url['user'] ?? '';
        $this->pass   = $url['pass'] ?? '';
        $this->path   = $url['path'] ?? '';

        if (StringUtils::endsWith($this->path, '.php')) {
            $path = \substr($this->path, 0, -4);

            if ($path === false) {
                throw new \Exception(); // @codeCoverageIgnore
            }

            $this->path = $path;
        }

        $this->pathElements = \explode('/', \trim($this->path, '/'));

        $path             = \array_slice($this->pathElements, $this->pathOffset);
        $this->offsetPath = '/' . \implode('/', $path);

        $this->queryString = $url['query'] ?? '';

        if (!empty($this->queryString)) {
            \parse_str($this->queryString, $this->query);
        }

        $this->query = \array_change_key_case($this->query, \CASE_LOWER);

        $this->fragment  = $url['fragment'] ?? '';
        $this->fragments = \explode('&', $url['fragment'] ?? '');
        $this->base      = $this->scheme . '://' . $this->host . ($this->port !== 80 ? ':' . $this->port : '') . $this->rootPath;
    }

    /**
     * Get current uri.
     *
     * @return string Returns the current uri
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public static function getCurrent() : string
    {
        /** @noinspection PhpUndefinedConstantInspection */
        return ((!empty($_SERVER['HTTPS'] ?? '') && ($_SERVER['HTTPS'] ?? '') !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
            || (($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '') === 'on') ? 'https' : 'http')
            . '://' . ($_SERVER['HTTP_HOST'] ?? ''). ($_SERVER['REQUEST_URI'] ?? '');
    }

    /**
     * Create uri from current url
     *
     * @return HttpUri Returns the current uri
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public static function fromCurrent() : self
    {
        return new self(self::getCurrent());
    }

    /**
     * {@inheritdoc}
     */
    public static function isValid(string $uri) : bool
    {
        return (bool) \filter_var($uri, \FILTER_VALIDATE_URL);
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
        $this->rootPath = \rtrim($root, '/');
        $this->base     = $this->scheme . '://' . $this->host . ($this->port !== 80 ? ':' . $this->port : '') . $this->rootPath;
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
     * Return the subdomain of a host
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getSubdomain() : string
    {
        $host   = \explode('.', $this->host);
        $length = \count($host) - 2;

        if ($length < 1) {
            return '';
        }

        return \implode('.', \array_slice($host, 0, $length));
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(int $offset = 0) : string
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
        return $path . (empty($query) ? '' : '?' . $this->getQuery());
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(?string $key = null) : string
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
    public function setQuery(string $uri) : void
    {
        \parse_str($uri, $this->query);

        $this->query = \array_change_key_case($this->query, \CASE_LOWER);
    }

    /**
     * Add query parameter
     *
     * @param string $key   Parameter key
     * @param mixed  $value Value (null = omitted)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addQuery(string $key, mixed $value = null) : void
    {
        $key               = \strtolower($key);
        $this->query[$key] = $value;

        $toAdd = (empty($this->queryString) ? '?' : '&')
            . $key
            . ($value === null ? '' : '=' . ((string) $value));

        $this->queryString .= $toAdd;

        // @todo handle existing string at the end of uri (e.g. #fragment)
        $this->uri .= $toAdd;
    }

    /**
     * {@inheritdoc}
     */
    public function getPathElement(int $pos = 0, bool $useOffset = true) : string
    {
        return $this->pathElements[$pos + ($useOffset ? $this->pathOffset : 0)] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getPathKey(string $key) : string
    {
        foreach ($this->pathElements as $index => $element) {
            if ($element === $key) {
                return $this->pathElements[$index + 1] ?? '';
            }
        }

        return '';
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
        return ($this->user !== '' ? $this->user . '@' : '') . $this->host
            . ($this->port !== 0 ? ':' . $this->port : '');
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo() : string
    {
        return $this->user . (empty($this->pass) ? '' : ':' . $this->pass);
    }
}
