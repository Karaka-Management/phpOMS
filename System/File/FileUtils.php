<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\System\File
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\System\File;

/**
 * Path exception class.
 *
 * @package phpOMS\System\File
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class FileUtils
{
    public const CODE_EXTENSION         = ['cpp', 'c', 'h', 'hpp', 'cs', 'css', 'scss', 'htm', 'html', 'js', 'java', 'sh', 'vb', 'php', 'rb', 'rs', 'ts', 'swift', 'class', 'htaccess', 'sql', 'py', 'bat', 'xml'];
    public const TEXT_EXTENSION         = ['log', 'txt', 'md', 'csv', 'tex', 'latex', 'cfg', 'json', 'config', 'conf', 'ini', 'yaml', 'yml'];
    public const WORD_EXTENSION         = ['doc', 'docx', 'rtf', 'odt'];
    public const PRESENTATION_EXTENSION = ['ppt', 'pptx', 'pps', 'odp', 'key'];
    public const PDF_EXTENSION          = ['pdf'];
    public const ARCHIVE_EXTENSION      = ['zip', '7z', 'rar', 'tar', 'gz', 'z', 'deb', 'rpm', 'pkg'];
    public const AUDIO_EXTENSION        = ['mp3', 'wav', 'wma', 'ogg'];
    public const VIDEO_EXTENSION        = ['mp4', 'flv', 'vob', 'wmv', 'swf', 'mpg', 'mpeg', 'mov', 'mkv', 'h264', 'avi'];
    public const SPREADSHEET_EXTENSION  = ['xls', 'xlsm', 'xlr', 'ods'];
    public const IMAGE_EXTENSION        = ['png', 'gif', 'jpg', 'jpeg', 'tif', 'tiff', 'bmp', 'svg', 'ico'];
    public const DIRECTORY              = ['collection', '/'];
    public const SYSTEM_EXTENSION       = ['bak', 'dll', 'sys', 'tmp', 'msi', 'so', 'exe', 'bin', 'iso'];

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
        if (!\file_exists($origPath) || \realpath($origPath) === false) {
            $startsWithSlash = \strpos($origPath, '/') === 0 ? '/' : '';

            $path  = [];
            $parts = \explode('/', $origPath);

            foreach ($parts as $part) {
                if (empty($part) || $part === '.') {
                    continue;
                }

                if ($part !== '..') {
                    $path[] = $part;
                } elseif (!empty($path)) {
                    \array_pop($path);
                } else {
                    throw new PathException($origPath);
                }
            }

            return $startsWithSlash . \implode('/', $path);
        }

        return \realpath($origPath);
    }

    /**
     * Change encoding of file
     *
     * @param string $file     Path to file which should be re-encoded
     * @param string $encoding New file encoding
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function changeFileEncoding(string $file, string $encoding) : void
    {
        $content = \file_get_contents($file);

        if ($content === false) {
            return;
        }

        $detected = \mb_detect_encoding($content);
        \file_put_contents($file, \mb_convert_encoding($content, $encoding, $detected === false ? \mb_list_encodings() : $detected));
    }

    /**
     * Converts a string permisseion (rwx) to octal
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
                $perm          .= $tempPermission;
                $tempPermission = 0;
            }
        }

        return \intval($perm, 8);
    }
}
