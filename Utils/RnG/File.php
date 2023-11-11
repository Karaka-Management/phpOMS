<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\RnG
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\RnG;

/**
 * File generator.
 *
 * @package phpOMS\Utils\RnG
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class File
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
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

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

        $key = \array_rand($source, 1);

        return $source[$key][\array_rand($source[$key], 1)];
    }
}
