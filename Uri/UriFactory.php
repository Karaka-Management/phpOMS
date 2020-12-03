<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Uri
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Uri;

/**
 * UriFactory class.
 *
 * Used in order to create a uri
 *
 * @package phpOMS\Uri
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class UriFactory
{
    /**
     * Dynamic query elements.
     *
     * @var string[]
     * @since 1.0.0
     */
    private static array $uri = [];

    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Set global query replacements.
     *
     * @param string $key Replacement key
     *
     * @return null|string
     *
     * @since 1.0.0
     */
    public static function getQuery(string $key) : ?string
    {
        return self::$uri[$key] ?? null;
    }

    /**
     * Cleanup
     *
     * @param string $identifier Identifier for cleaning up (e.g. * = everything, / = only path, ? = only query parameters, # only fragment etc.)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function clean(string $identifier = '?') : void
    {
        if ($identifier === '*') {
            self::$uri = [];
        } else {
            foreach (self::$uri as $key => $value) {
                if (\stripos($key, $identifier) === 0) {
                    unset(self::$uri[$key]);
                }
            }
        }
    }

    /**
     * Set global query replacements.
     *
     * @param string $key       Replacement key
     * @param string $value     Replacement value
     * @param bool   $overwrite Overwrite if already exists
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function setQuery(string $key, string $value, bool $overwrite = false) : bool
    {
        if ($overwrite || !isset(self::$uri[$key])) {
            self::$uri[$key] = $value;

            return true;
        }

        return false;
    }

    /**
     * Setup uri builder based on current request
     *
     * @param UriInterface $uri Uri
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function setupUriBuilder(UriInterface $uri) : void
    {
        self::setQuery('/scheme', $uri->scheme);
        self::setQuery('/host', $uri->host);
        self::setQuery('/port', (string) $uri->port);
        self::setQuery('/base', \rtrim($uri->getBase(), '/'));
        self::setQuery('/rootPath', $uri->getRootPath());
        self::setQuery('?', '?' . $uri->getQuery());
        self::setQuery('%', $uri->__toString());
        self::setQuery('#', $uri->fragment);
        self::setQuery('/', $uri->getPath());
        self::setQuery(':user', $uri->user);
        self::setQuery(':pass', $uri->pass);

        $data = $uri->getPathElements();
        foreach ($data as $key => $value) {
            self::setQuery('/' . $key, $value);
        }

        $data = $uri->getQueryArray();
        foreach ($data as $key => $value) {
            self::setQuery('?' . $key, $value, true);
        }
    }

    /**
     * Clear uri component
     *
     * @param string $key Uri component key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function clear(string $key) : bool
    {
        if (isset(self::$uri[$key])) {
            unset(self::$uri[$key]);

            return true;
        }

        return false;
    }

    /**
     * Clear uri components that follow a certain pattern
     *
     * @param string $pattern Uri key pattern to remove
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function clearLike(string $pattern) : bool
    {
        $success = false;

        foreach (self::$uri as $key => $value) {
            if (((bool) \preg_match('~^' . $pattern . '$~', $key))) {
                unset(self::$uri[$key]);
                $success = true;
            }
        }

        return $success;
    }

    /**
     * Simplify url
     *
     * While adding, and removing elements to a uri it can have multiple parameters or empty parameters which need to be cleaned up
     *
     * @param string $url Url to simplify
     *
     * @return string
     *
     * @since 1.0.0
     */
    private static function unique(string $url) : string
    {
        // handle edge cases / normalization
        $url = \str_replace(
                ['=%', '=#', '=?'],
                ['=%25', '=%23', '=%3F'],
                $url
            );

        /** @var array $urlStructure */
        $urlStructure = \parse_url($url);
        if ($urlStructure === false) {
            return $url; // @codeCoverageIgnore
        }

        if (isset($urlStructure['query'])) {
            $len = \strlen($urlStructure['query']);
            for ($i = 0; $i < $len; ++$i) {
                if ($urlStructure['query'][$i] === '?') {
                    $urlStructure['query'] = \substr_replace($urlStructure['query'], '&', $i, 1);
                } elseif ($urlStructure['query'][$i] === '\\') {
                    ++$i;
                }
            }

            \parse_str($urlStructure['query'], $urlStructure['query']);

            foreach ($urlStructure['query'] as $para => $query) {
                if ($query === '' && \stripos($url, $para . '=') !== false) {
                    unset($urlStructure['query'][$para]);
                }
            }
        }

        $escaped =
            (isset($urlStructure['scheme']) && !empty($urlStructure['scheme'])
                ? $urlStructure['scheme'] . '://' : '')
            . (isset($urlStructure['username'])
                ? $urlStructure['username'] . ':' : '')
            . (isset($urlStructure['password'])
                ? $urlStructure['password'] . '@' : '')
            . (isset($urlStructure['host']) && !empty($urlStructure['host'])
                ? $urlStructure['host'] : '')
            . (isset($urlStructure['port']) && !empty($urlStructure['port'])
                ? ':' . $urlStructure['port'] : '')
            . (isset($urlStructure['path']) && !empty($urlStructure['path'])
                ? $urlStructure['path'] : '')
            . (isset($urlStructure['query']) && !empty($urlStructure['query'])
                ? '?' . \http_build_query($urlStructure['query']) : '')
            . (isset($urlStructure['fragment']) && !empty($urlStructure['fragment'])
                ? '#' . \str_replace('\#', '#', $urlStructure['fragment']) : '');

        return \str_replace(
                ['%5C%7B', '%5C%7D', '%5C%3F', '%5C%23'],
                ['{', '}', '?', '#'],
                $escaped
            );
    }

    /**
     * Build uri.
     *
     * # = DOM id
     * . = DOM class
     * / = Current path
     * ? = Current query
     * @ =
     * $ = Other data
     *
     * @param string                               $uri     Path data
     * @param array<string, bool|int|float|string> $toMatch Optional special replacements
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    public static function build(string $uri, array $toMatch = []) : string
    {
        if (\stripos($uri, '{') === false) {
            return $uri;
        }

        $parsed = \preg_replace_callback('(\{[\/#\?%@\.\$][a-zA-Z0-9\-]*\})', function ($match) use ($toMatch) {
            $match = \substr($match[0], 1, \strlen($match[0]) - 2);

            return $toMatch[$match] ?? self::$uri[$match] ?? '';
        }, $uri);

        return self::unique($parsed ?? '');
    }
}
