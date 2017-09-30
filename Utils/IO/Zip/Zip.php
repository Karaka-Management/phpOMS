<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Zip;

use phpOMS\System\File\FileUtils;
use phpOMS\Utils\StringUtils;

/**
 * Zip class for handling zip files.
 *
 * Providing basic zip support
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Zip implements ArchiveInterface
{

    /**
     * {@inheritdoc}
     */
    public static function pack($sources, string $destination, bool $overwrite = true) : bool
    {
        $destination = FileUtils::absolute(str_replace('\\', '/', $destination));

        if (!$overwrite && file_exists($destination)) {
            return false;
        }

        $zip = new \ZipArchive();
        if (!$zip->open($destination, $overwrite ? \ZipArchive::CREATE | \ZipArchive::OVERWRITE : \ZipArchive::CREATE)) {
            return false;
        }

        /** @var array $sources */
        foreach ($sources as $source => $relative) {
            $source = str_replace('\\', '/', realpath($source));

            if (!file_exists($source)) {
                continue;
            }

            if (is_dir($source)) {
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

                foreach ($files as $file) {
                    $file = str_replace('\\', '/', $file);

                    /* Ignore . and .. */
                    if (in_array(mb_substr($file, mb_strrpos($file, '/') + 1), ['.', '..'])) {
                        continue;
                    }

                    $file = realpath($file);

                    if (is_dir($file)) {
                        $zip->addEmptyDir(str_replace($relative . '/', '', $file . '/'));
                    } elseif (is_file($file)) {
                        $zip->addFile(str_replace($relative . '/', '', $file), $file);
                    }
                }
            } elseif (is_file($source)) {
                $zip->addFile($source, $relative);
            }
        }

        return $zip->close();
    }
    
    /**
     * {@inheritdoc}
     */
    public static function unpack(string $source, string $destination) : bool
    {
        $destination = str_replace('\\', '/', realpath($destination));

        if (file_exists($destination)) {
            return false;
        }

        $zip = new \ZipArchive();
        if (!$zip->open($destination)) {
            return false;
        }
        
        $zip->extractTo($destination);
        
        return $zip->close();
    }
}
