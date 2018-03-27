<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\RnG;

/**
 * File generator.
 *
 * @package    DataStorage
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class File
{

    /**
     * Extensions.
     *
     * @var array[]
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
     * @param array                $source       Source array for possible extensions
     * @param DistributionType|int $distribution Distribution type for the extensions
     *
     * @return false|array
     *
     * @since  1.0.0
     */
    public static function generateExtension($source = null, $distribution = DistributionType::UNIFORM)
    {
        if ($source === null) {
            $source = self::$extensions;
        }

        switch ($distribution) {
            case DistributionType::UNIFORM:
                $key = rand(0, count($source) - 1);
                break;
            default:
                return false;
        }

        return $source[$key][0];
    }
}
