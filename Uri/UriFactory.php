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
 * UriFactory class.
 *
 * Used in order to create a uri
 *
 * @category   Framework
 * @package    phpOMS/Uri
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class UriFactory
{

    /**
     * Dynamic query elements.
     *
     * @var string[]
     * @since 1.0.0
     */
    private static $uri = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function __construct()
    {
    }

    /**
     * Set global query replacements.
     *
     * @param string $key Replacement key
     *
     * @return false|string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getQuery(string $key)
    {
        return self::$uri[$key] ?? false;
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function setQuery(string $key, string $value, bool $overwrite = true) : bool
    {
        if ($overwrite || !isset(self::$uri[$key])) {
            self::$uri[$key] = $value;

            return true;
        }

        return false;
    }

    private static function unique(string $url) : string
    {
        $parts = explode('?', $url);

        if (count($parts) >= 2) {
            $full = $parts[1];
            $pars  = explode('&', $full);
            $comps = [];
            $spl   = null;
            $length = count($pars);

            for ($i = 0; $i < $length; $i++) {
                $spl           = explode('=', $pars[$i]);
                $comps[$spl[0]] = $spl[1];
            }

            $pars = [];
            foreach($comps as $key => $value) {
                $pars[] = $key . '=' . $value;
            }

            $url = $parts[0] . '?' . implode('&', $pars);
        }

        return $url;
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
     * @param string $uri     Path data
     * @param array  $toMatch Optional special replacements
     *
     * @return null|string
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function build(string $uri, array $toMatch = [])
    {
        $parsed = preg_replace_callback('(\{[\/#\?@\.\$][a-zA-Z0-9\-]*\})', function ($match) use ($toMatch) {
            $match = substr($match[0], 1, strlen($match[0]) - 2);

            return $toMatch[$match] ?? self::$uri[$match] ?? $match;
        }, $uri);

        // todo: maybe don't do this and adjust unique?!
        if(strpos($parsed, '?')) {
            str_replace('&', '?', $parsed);
        }

        $parsed = self::unique($parsed);

        return $parsed;
    }
}
