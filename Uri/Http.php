<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Uri;

use phpOMS\Utils\StringUtils;

/**
 * Uri interface.
 *
 * Used in order to create and evaluate a uri
 *
 * @category   Framework
 * @package    phpOMS/Uri
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Http implements UriInterface
{

    /**
     * Root path.
     *
     * @var string
     * @since 1.0.0
     */
    private $rootPath = '/';

    /**
     * Uri.
     *
     * @var string
     * @since 1.0.0
     */
    private $uri = '';

    /**
     * Uri scheme.
     *
     * @var string
     * @since 1.0.0
     */
    private $scheme = '';

    /**
     * Uri host.
     *
     * @var string
     * @since 1.0.0
     */
    private $host = '';

    /**
     * Uri port.
     *
     * @var int
     * @since 1.0.0
     */
    private $port = 80;

    /**
     * Uri user.
     *
     * @var string
     * @since 1.0.0
     */
    private $user = '';

    /**
     * Uri password.
     *
     * @var string
     * @since 1.0.0
     */
    private $pass = '';

    /**
     * Uri path.
     *
     * @var string
     * @since 1.0.0
     */
    private $path = '';

    /**
     * Uri query.
     *
     * @var array
     * @since 1.0.0
     */
    private $query = [];

    /**
     * Uri query.
     *
     * @var string
     * @since 1.0.0
     */
    private $queryString = '';

    /**
     * Uri fragment.
     *
     * @var string
     * @since 1.0.0
     */
    private $fragment = '';

    /**
     * Uri base.
     *
     * @var string
     * @since 1.0.0
     */
    private $base = '';

    /**
     * Constructor.
     *
     * @param string $uri Root path for subdirectory
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $uri)
    {
        $this->set($uri);
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $uri) /* : void */
    {
        $this->uri = $uri;
        $url       = parse_url($this->uri);

        $this->scheme = $url['scheme'] ?? '';
        $this->host   = $url['host'] ?? null;
        $this->port   = $url['port'] ?? null;
        $this->user   = $url['user'] ?? null;
        $this->pass   = $url['pass'] ?? null;
        $this->path   = $url['path'] ?? '';

        if (StringUtils::endsWith($this->path, '.php')) {
            $this->path = substr($this->path, 0, -4);
        }

        $this->path        = strpos($this->path, $this->rootPath) === 0 ? substr($this->path, strlen($this->rootPath), strlen($this->path)) : $this->path; // TODO: this could cause a bug if the rootpath is the same as a regular path which is usually the language
        $this->queryString = $url['query'] ?? null;

        if (isset($this->queryString)) {
            parse_str($this->queryString, $this->query);
        }

        $this->fragment = $url['fragment'] ?? null;
        $this->base     = $this->scheme . '://' . $this->host . $this->rootPath;
    }

    /**
     * Get current uri.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getCurrent() : string
    {
        /** @noinspection PhpUndefinedConstantInspection */
        return 'http://' . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '');
    }

    /**
     * {@inheritdoc}
     */
    public static function isValid(string $uri) : bool
    {
        return filter_var($uri, FILTER_VALIDATE_URL);
    }

    /**
     * Get root path.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getRootPath() : string
    {
        return $this->rootPath;
    }

    /**
     * {@inheritdoc}
     */
    public function setRootPath(string $root) /* : void */
    {
        $this->rootPath = $root;
        $this->set($this->uri);
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme() : string
    {
        return $this->scheme ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getHost() : string
    {
        return $this->host ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getPort() : int
    {
        return $this->port ?? 80;
    }

    /**
     * Get password.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPass() : string
    {
        return $this->pass ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getPath() : string
    {
        return $this->path ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute() : string
    {
        return ($this->path ?? '') . '?' . $this->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function getPathElement(int $pos) : string
    {
        return explode('/', $this->path)[$pos];
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(string $key = null)
    {
        return isset($key) ? $this->query[$key] ?? null : $this->queryString ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getFragment() : string
    {
        return $this->fragment ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getBase() : string
    {
        return $this->base ?? '';
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
        return ($this->getUser() !== '' ? $this->getUser() . '@' : '') . $this->host . (isset($this->port) && $this->port !== 0 ? ':' . $this->port : '');
    }

    /**
     * Get user.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getUser() : string
    {
        return $this->user ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo() : string
    {
        return $this->user . (isset($this->pass) && $this->pass !== '' ? ':' . $this->pass : '');
    }
}
