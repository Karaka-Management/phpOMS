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



/**
 * Uri interface.
 *
 * Used in order to create and evaluate a uri
 *
 * @category   Uri
 * @package    Framework
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
     * @var string
     * @since 1.0.0
     */
    private $query = null;

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
    public function setRootPath(string $root)
    {
        return $this->rootPath = $root;
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
        return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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
     * Get scheme.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getScheme() : string
    {
        return $this->scheme;
    }

    /**
     * Get host.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getHost() : string
    {
        return $this->host;
    }

    /**
     * Get port.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPort() : int
    {
        return $this->port;
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
        return $this->pass;
    }

    /**
     * Get path.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Get query.
     *
     * @param null|string $key Query key
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getQuery(string $key = null)
    {
        return isset($key) ? $this->query[$key] ?? null : $this->query;
    }

    /**
     * Get fragment.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getFragment() : string
    {
        return $this->fragment;
    }

    /**
     * Get base.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getBase() : string
    {
        return $this->base;
    }

    /**
     * Set uri.
     *
     * @param string $uri Uri
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function set(string $uri)
    {
        $this->uri = $uri;

        $url = parse_url($this->uri);

        $this->scheme = $url['scheme'] ?? '';
        $this->host   = $url['host'] ?? null;
        $this->port   = $url['port'] ?? null;
        $this->user   = $url['user'] ?? null;
        $this->pass   = $url['pass'] ?? null;
        $this->path   = $url['path'] ?? null;
        $this->path   = rtrim($this->path, '.php');
        $this->path   = strpos($this->path, $this->rootPath) === 0 ? substr($this->path, strlen($this->rootPath), strlen($this->path))  : $this->path; // TODO: this could cause a bug if the rootpath is the same as a regular path which is usually the language
        $this->query  = $url['query'] ?? null;

        if (isset($this->query)) {
            parse_str($this->query, $this->query);
        }

        $this->fragment = $url['fragment'] ?? null;

        $this->base = $this->scheme . '://' . $this->host . $this->rootPath;
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
        return ($this->getUser() !== '' ? $this->getUser() . '@' : '') . $this->host . (isset($this->port) && $this->port !== '' ? ':' . $this->port : '');
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
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo() : string
    {
        return $this->user . (isset($this->pass) && $this->pass !== '' ? ':' . $this->pass : '');
    }
}
