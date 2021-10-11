<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Utils\RnG
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\RnG;

/**
 * File generator.
 *
 * @package phpOMS\Utils\RnG
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class File
{
    /**
     * Extensions.
     *
     * @var array<string[]>
     * @since 1.0.0
     */
    private static $extensions = [
        ['exe'], ['dat'], ['txt'], ['csv', 'txt'], ['doc'], ['docx', 'doc'],
        ['mp3'], ['mp4'], ['avi'], ['mpeg'], ['wmv'], ['ppt'],
        ['xls'], ['xlsx', 'xls'], ['xlsxm', 'xls'], ['php'], ['html'], ['tex'],
        ['js'], ['c'], ['cpp'], ['h'], ['res'], ['ico'],
        ['jpg'], ['png'], ['gif'], ['bmp'], ['ttf'], ['zip'],
        ['rar'], ['7z'], ['tar', 'gz'], ['gz'], ['gz'], ['sh'],
        ['bat'], ['iso'], ['css'], ['json'], ['ini'], ['psd'],
        ['pptx', 'ppt'], ['xml'], ['dll'], ['wav'], ['wma'], ['vb'],
        ['tmp'], ['tif'], ['sql'], ['swf'], ['svg'], ['rpm'],
        ['rss'], ['pkg'], ['pdf'], ['mpg'], ['mov'], ['jar'],
        ['flv'], ['fla'], ['deb'], ['py'], ['pl'],
    ];

    /**
     * Get a random file extension.
     *
     * @param array<string[]> $source Source array for possible extensions
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function generateExtension(array $source = null) : string
    {
        if ($source === null) {
            $source = self::$extensions;
        }

        $key = mt_rand(0, \count($source) - 1);

        return $source[$key][mt_rand(0, \count($source[$key]) - 1)];
    }
}
