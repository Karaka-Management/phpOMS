<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\System\File
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System\File;

/**
 * Path exception class.
 *
 * @package phpOMS\System\File
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class FileUtils
{
    public const CODE_EXTENSION = ['cpp', 'c', 'h', 'hpp', 'cs', 'css', 'scss', 'htm', 'html', 'js', 'java', 'sh', 'vb', 'php', 'rb', 'rs', 'ts', 'swift', 'class', 'htaccess', 'sql', 'py', 'bat', 'xml'];

    public const TEXT_EXTENSION = ['log', 'txt', 'md', 'csv', 'tex', 'latex', 'cfg', 'json', 'config', 'conf', 'ini', 'yaml', 'yml'];

    public const WORD_EXTENSION = ['doc', 'docx', 'rtf', 'odt'];

    public const PRESENTATION_EXTENSION = ['ppt', 'pptx', 'pps', 'odp', 'key'];

    public const PDF_EXTENSION = ['pdf'];

    public const ARCHIVE_EXTENSION = ['zip', '7z', 'rar', 'tar', 'gz', 'z', 'deb', 'rpm', 'pkg'];

    public const AUDIO_EXTENSION = ['mp3', 'wav', 'wma', 'ogg'];

    public const VIDEO_EXTENSION = ['mp4', 'flv', 'vob', 'wmv', 'swf', 'mpg', 'mpeg', 'mov', 'mkv', 'h264', 'avi'];

    public const SPREADSHEET_EXTENSION = ['xls', 'xlsx', 'xlsm', 'xlr', 'ods'];

    public const IMAGE_EXTENSION = ['png', 'gif', 'jpg', 'jpeg', 'tif', 'tiff', 'bmp', 'svg', 'ico'];

    public const DIRECTORY = ['collection', '/'];

    public const SYSTEM_EXTENSION = ['bak', 'dll', 'sys', 'tmp', 'msi', 'so', 'exe', 'bin', 'iso'];

    public const REFERENCE = ['reference'];

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
     * Get file extension type.
     *
     * @param string $extension Extension string
     *
     * @return int Extension type
     *
     * @since 1.0.0
     */
    public static function getExtensionType(string $extension) : int
    {
        $extension = \strtolower($extension);

        if (\in_array($extension, self::CODE_EXTENSION)) {
            return ExtensionType::CODE;
        } elseif (\in_array($extension, self::TEXT_EXTENSION)) {
            return ExtensionType::TEXT;
        } elseif (\in_array($extension, self::WORD_EXTENSION)) {
            return ExtensionType::WORD;
        } elseif (\in_array($extension, self::PRESENTATION_EXTENSION)) {
            return ExtensionType::PRESENTATION;
        } elseif (\in_array($extension, self::PDF_EXTENSION)) {
            return ExtensionType::PDF;
        } elseif (\in_array($extension, self::ARCHIVE_EXTENSION)) {
            return ExtensionType::ARCHIVE;
        } elseif (\in_array($extension, self::AUDIO_EXTENSION)) {
            return ExtensionType::AUDIO;
        } elseif (\in_array($extension, self::VIDEO_EXTENSION)) {
            return ExtensionType::VIDEO;
        } elseif (\in_array($extension, self::IMAGE_EXTENSION)) {
            return ExtensionType::IMAGE;
        } elseif (\in_array($extension, self::SPREADSHEET_EXTENSION)) {
            return ExtensionType::SPREADSHEET;
        } elseif (\in_array($extension, self::DIRECTORY)) {
            return ExtensionType::DIRECTORY;
        } elseif (\in_array($extension, self::REFERENCE)) {
            return ExtensionType::REFERENCE;
        }

        return ExtensionType::UNKNOWN;
    }

    /**
     * Make file path absolute
     *
     * @param string $origPath File path
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function absolute(string $origPath) : string
    {
        if (\file_exists($origPath)) {
            $path = \realpath($origPath);

            return $path === false ? '' : $path;
        }

        $startsWithSlash = \str_starts_with($origPath, '/') || \str_starts_with($origPath, '\\') ? '/' : '';

        $path  = [];
        $parts = \explode('/', $origPath);

        foreach ($parts as $part) {
            if (empty($part) || $part === '.') {
                continue;
            }

            if ($part !== '..' || empty($path)) {
                $path[] = $part;
            } else {
                \array_pop($path);
            }
        }

        return $startsWithSlash . \implode('/', $path);
    }

    /**
     * Change encoding of file
     *
     * @param string $input          Path to file which should be re-encoded
     * @param string $output         Output file path
     * @param string $outputEncoding New file encoding
     * @param string $inputEncoding  Old file encoding
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function changeFileEncoding(string $input, string $output, string $outputEncoding, string $inputEncoding = '') : void
    {
        $content = \file_get_contents($input);

        if ($content === false) {
            return; // @codeCoverageIgnore
        }

        $detected = empty($inputEncoding) ? \mb_detect_encoding($content) : $inputEncoding;
        \file_put_contents($output, \mb_convert_encoding($content, $outputEncoding, $detected === false ? \mb_list_encodings() : $detected));
    }

    /**
     * Converts a string permission (rwx) to octal
     *
     * @param string $permission Permission string (e.g. rwx-w-r--)
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function permissionToOctal(string $permission) : int
    {
        $permissionLength = \strlen($permission);
        $perm             = '';
        $tempPermission   = 0;

        for ($i = 0; $i < $permissionLength; ++$i) {
            if ($permission[$i] === 'r') {
                $tempPermission += 4;
            } elseif ($permission[$i] === 'w') {
                $tempPermission += 2;
            } elseif ($permission[$i] === 'x') {
                ++$tempPermission;
            }

            if (($i + 1) % 3 === 0) {
                $perm .= $tempPermission;
                $tempPermission = 0;
            }
        }

        return \intval($perm, 8);
    }

    /**
     * Multi-byte-safe pathinfo.
     *
     * @param string          $path    Path
     * @param null|int|string $options PATHINFO_* or specifier for the component
     *
     * @return string|array
     *
     * @since 1.0.0
     */
    public static function mb_pathinfo(string $path, int | string|null $options = null) : string | array
    {
        $ret      = ['dirname' => '', 'basename' => '', 'extension' => '', 'filename' => ''];
        $pathinfo = [];

        if (\preg_match('#^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^.\\\\/]+?)|))[\\\\/.]*$#m', $path, $pathinfo)) {
            if (isset($pathinfo[1])) {
                $ret['dirname'] = $pathinfo[1];
            }
            if (isset($pathinfo[2])) {
                $ret['basename'] = $pathinfo[2];
            }
            if (isset($pathinfo[5])) {
                $ret['extension'] = $pathinfo[5];
            }
            if (isset($pathinfo[3])) {
                $ret['filename'] = $pathinfo[3];
            }
        }

        switch ($options) {
            case \PATHINFO_DIRNAME:
            case 'dirname':
                return $ret['dirname'];
            case \PATHINFO_BASENAME:
            case 'basename':
                return $ret['basename'];
            case \PATHINFO_EXTENSION:
            case 'extension':
                return $ret['extension'];
            case \PATHINFO_FILENAME:
            case 'filename':
                return $ret['filename'];
            default:
                return $ret;
        }
    }

    /**
     * Check whether a file path is safe, accessible, and readable.
     *
     * @param string $path A relative or absolute path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isAccessible(string $path) : bool
    {
        if (!self::isPermittedPath($path)) {
            return false;
        }

        $readable = \is_file($path);
        if (!\str_starts_with($path, '\\\\')) {
            $readable = $readable && \is_readable($path);
        }

        return $readable;
    }

    /**
     * Check whether a file path is of a permitted type.
     *
     * @param string $path Path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isPermittedPath(string $path) : bool
    {
        return !\preg_match('#^[a-z][a-z\d+.-]*://#i', $path);
    }

    /**
     * Turn a string into a safe file name (sanitize a string)
     *
     * @param string $name String to sanitize for file name usage
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function makeSafeFileName(string $name) : string
    {
        $name = \preg_replace("/[^A-Za-z0-9\-_.]/", '_', $name);
        $name = \preg_replace("/_+/", '_', $name ?? '');
        $name = \trim($name ?? '', '_');

        return \strtolower($name);
    }
}
