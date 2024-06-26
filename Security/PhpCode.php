<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Security
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Security;

/**
 * Php code security class.
 *
 * This can be used to ensure php code doesn't contain malicious functions and or characters.
 * Additionally, this can also be used in order verify that the source code is not altered compared to some expected source code.
 *
 * @package phpOMS\Security
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class PhpCode
{
    /**
     * Disabled functions
     *
     * @var string[]
     * @since 1.0.0
     */
    public static array $disabledFunctions = [
        'apache_child_terminate', 'apache_setenv', 'define_syslog_variables', 'eval',
        'exec', 'fp', 'fput', 'ftp_connect', 'ftp_exec', 'ftp_get', 'ftp_login', 'ftp_nb_fput', 'ftp_put', 'ftp_raw',
        'ftp_rawlist', 'highlight_file', 'ini_alter', 'ini_get_all', 'ini_restore', 'inject_code', 'mysql_pconnect',
        'openlog', 'php_uname', 'phpAds_remoteInfo', 'phpAds_XmlRpc', 'phpAds_xmlrpcDecode',
        'phpAds_xmlrpcEncode', 'popen', 'posix_getpwuid', 'posix_kill', 'posix_mkfifo', 'posix_setpgid', 'posix_setsid',
        'posix_setuid', 'posix_uname', 'proc_close', 'proc_get_status', 'shell_exec', 'serialize', 'unserialize', '__serialize', '__unserialize',
    ];

    /**
     * Deprecated functions
     *
     * @var string[]
     * @since 1.0.0
     */
    public static array $deprecatedFunctions = [
        'apache_child_terminate', 'apache_setenv', 'define_syslog_variables', 'eval',
        'exec', 'fp', 'fput', 'ftp_connect', 'ftp_exec', 'ftp_get', 'ftp_login', 'ftp_nb_fput', 'ftp_put', 'ftp_raw',
        'ftp_rawlist', 'highlight_file', 'ini_alter', 'ini_get_all', 'ini_restore', 'inject_code', 'mysql_pconnect',
        'openlog', 'php_uname', 'phpAds_remoteInfo', 'phpAds_XmlRpc', 'phpAds_xmlrpcDecode',
        'phpAds_xmlrpcEncode', 'popen', 'posix_getpwuid', 'posix_kill', 'posix_mkfifo', 'posix_setpgid', 'posix_setsid',
        'posix_setuid', 'posix_uname', 'proc_close', 'proc_get_status', 'shell_exec',
    ];

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
     * Normalize source code for inspection
     *
     * @param string $source Source code
     *
     * @return string Normalized source code
     *
     * @since 1.0.0
     */
    public static function normalizeSource(string $source) : string
    {
        return \str_replace(["\n", "\r\n", "\r", "\t"], ['', '', '', ' '], $source);
    }

    /**
     * Check if has source unicode
     *
     * @param string $source Source code
     *
     * @return bool Returns true if the code has unicode characters otherwise false is returned
     *
     * @since 1.0.0
     */
    public static function hasUnicode(string $source) : bool
    {
        $length = \mb_strlen($source, 'UTF-8');

        for ($i = 0; $i < $length; ++$i) {
            $char      = \mb_substr($source, $i, 1, 'UTF-8');
            $codePoint = \ord($char);

            if ($codePoint > 127) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if function is disabled
     *
     * @param string[] $functions Functions to check
     *
     * @return bool Returns true if code has disabled function calls otherwise false is returned
     *
     * @since 1.0.0
     */
    public static function isDisabled(array $functions) : bool
    {
        $disabled = \ini_get('disable_functions');

        if ($disabled === false) {
            return true; // @codeCoverageIgnore
        }

        $disabled = \str_replace(' ', '', $disabled);
        $disabled = \explode(',', $disabled);

        foreach ($functions as $function) {
            if (!\in_array($function, $disabled)) {
                return false;
            }
        }

        return true; // @codeCoverageIgnore
    }

    /**
     * Check if has deprecated functions
     *
     * @param string $source Source code
     *
     * @return bool Returns true if code contains deprecated functions otherwise false is returned
     *
     * @since 1.0.0
     */
    public static function hasDeprecatedFunction(string $source) : bool
    {
        foreach (self::$deprecatedFunctions as $function) {
            if (\preg_match('/' . $function . '\s*\(/', $source) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate file integrity
     *
     * @param string $source Source code path
     * @param string $hash   Source hash (md5)
     *
     * @return bool Returns true if file matches expected signature otherwise false is returned
     *
     * @since 1.0.0
     */
    public static function validateFileIntegrity(string $source, string $hash) : bool
    {
        return \md5_file($source) === $hash;
    }

    /**
     * Validate code integrity
     *
     * @param string $source Source code
     * @param string $remote Remote code
     *
     * @return bool Returns true if source code is the same as the expected code otherwise false is returned
     *
     * @since 1.0.0
     */
    public static function validateStringIntegrity(string $source, string $remote) : bool
    {
        return $source === $remote;
    }
}
