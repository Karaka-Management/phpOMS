<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\System\File;

/**
 * Path exception class.
 *
 * @category   Framework
 * @package    phpOMS\System\File
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class FileUtils
{
    /* public */ const CODE_EXTENSION = ['cpp', 'c', 'h', 'hpp', 'cs', 'css', 'htm', 'html', 'php', 'rb'];
    /* public */ const TEXT_EXTENSION = ['doc', 'docx', 'txt', 'md', 'csv'];
    /* public */ const PRESENTATION_EXTENSION = ['ppt', 'pptx'];
    /* public */ const PDF_EXTENSION = ['pdf'];
    /* public */ const ARCHIVE_EXTENSION = ['zip', '7z', 'rar'];
    /* public */ const AUDIO_EXTENSION = ['mp3', 'wav'];
    /* public */ const VIDEO_EXTENSION = ['mp4'];
    /* public */ const SPREADSHEET_EXTENSION = ['xls', 'xlsm'];
    /* public */ const IMAGE_EXTENSION = ['png', 'gif', 'jpg', 'jpeg', 'tiff', 'bmp'];

    private function __construct() 
    {

    }

    public static function getExtensionType(string $extension) : int
    {
        $extension = strtolower($extension);

        if(in_array($extension, self::CODE_EXTENSION)) {
            return ExtensionType::CODE;
        } elseif(in_array($extension, self::TEXT_EXTENSION)) {
            return ExtensionType::TEXT;
        } elseif(in_array($extension, self::PRESENTATION_EXTENSION)) {
            return ExtensionType::PRESENTATION;
        } elseif(in_array($extension, self::PDF_EXTENSION)) {
            return ExtensionType::PDF;
        } elseif(in_array($extension, self::ARCHIVE_EXTENSION)) {
            return ExtensionType::ARCHIVE;
        } elseif(in_array($extension, self::AUDIO_EXTENSION)) {
            return ExtensionType::AUDIO;
        } elseif(in_array($extension, self::VIDEO_EXTENSION)) {
            return ExtensionType::VIDEO;
        } elseif(in_array($extension, self::IMAGE_EXTENSION)) {
            return ExtensionType::IMAGE;
        } elseif(in_array($extension, self::SPREADSHEET_EXTENSION)) {
            return ExtensionType::SPREADSHEET;
        }

        return ExtensionType::UNKNOWN;
    }
}