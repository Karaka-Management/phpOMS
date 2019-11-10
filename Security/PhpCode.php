<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Security
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Security;

/**
 * Php code security class.
 *
 * This can be used to ensure php code doesn't contain malicious functions and or characters.
 * Additionally this can also be used in order verify that the source code is not altered compared to some expected source code.
 *
 * @package phpOMS\Security
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class PhpCode
{
    /**
     * Disabled functions
     *
     * @var   array
     * @since 1.0.0
     */
    public static array $disabledFunctions = [
        'apache_child_terminate', 'apache_setenv', 'define_syslog_variables', 'escapeshellarg', 'escapeshellcmd', 'eval',
        'exec', 'fp', 'fput', 'ftp_connect', 'ftp_exec', 'ftp_get', 'ftp_login', 'ftp_nb_fput', 'ftp_put', 'ftp_raw',
        'ftp_rawlist', 'highlight_file', 'ini_alter', 'ini_get_all', 'ini_restore', 'inject_code', 'mysql_pconnect',
        'openlog', 'passthru', 'php_uname', 'phpAds_remoteInfo', 'phpAds_XmlRpc', 'phpAds_xmlrpcDecode',
        'phpAds_xmlrpcEncode', 'popen', 'posix_getpwuid', 'posix_kill', 'posix_mkfifo', 'posix_setpgid', 'posix_setsid',
        'posix_setuid', 'posix_uname', 'proc_close', 'proc_get_status',
    ];

    /**
     * Deprecated functions
     *
     * @var   array
     * @since 1.0.0
     */
    public static array $deprecatedFunctions = [
        'apache_child_terminate', 'apache_setenv', 'define_syslog_variables', 'escapeshellarg', 'escapeshellcmd', 'eval',
        'exec', 'fp', 'fput', 'ftp_connect', 'ftp_exec', 'ftp_get', 'ftp_login', 'ftp_nb_fput', 'ftp_put', 'ftp_raw',
        'ftp_rawlist', 'highlight_file', 'ini_alter', 'ini_get_all', 'ini_restore', 'inject_code', 'mysql_pconnect',
        'openlog', 'passthru', 'php_uname', 'phpAds_remoteInfo', 'phpAds_XmlRpc', 'phpAds_xmlrpcDecode',
        'phpAds_xmlrpcEncode', 'popen', 'posix_getpwuid', 'posix_kill', 'posix_mkfifo', 'posix_setpgid', 'posix_setsid',
        'posix_setuid', 'posix_uname', 'proc_close', 'proc_get_status',
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
        return (bool) \preg_match('/[^\x00-\x7f]/', $source);
    }

    /**
     * Check if function is disabled
     *
     * @param array<string> $functions Functions to check
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
     * Validate file integrety
     *
     * @param string $source Source code path
     * @param string $hash   Source hash
     *
     * @return bool Returns true if filee matches expected signature otherwise false is returned
     *
     * @since 1.0.0
     */
    public static function validateFileIntegrity(string $source, string $hash) : bool
    {
        return \md5_file($source) === $hash;
    }

    /**
     * Validate code integrety
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
