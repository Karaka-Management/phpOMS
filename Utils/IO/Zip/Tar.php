<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils\IO\Zip
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Zip;

use phpOMS\System\File\FileUtils;

/**
 * Zip class for handling zip files.
 *
 * Providing basic zip support
 *
 * @package    phpOMS\Utils\IO\Zip
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Tar implements ArchiveInterface
{
    /**
     * {@inheritdoc}
     */
    public static function pack($sources, string $destination, bool $overwrite = true) : bool
    {
        $destination = FileUtils::absolute(\str_replace('\\', '/', $destination));

        if (!$overwrite && \file_exists($destination)) {
            return false;
        }

        $tar = new \PharData($destination);

        /** @var array $sources */
        foreach ($sources as $source => $relative) {
            if (\is_numeric($source) && \realpath($relative) !== false) {
                $source   = $relative;
                $relative = '';
            }
            
            $source = \realpath($source);

            if ($source === false) {
                continue;
            }

            $source = \str_replace('\\', '/', $source);

            if (!\file_exists($source)) {
                continue;
            }

            if (\is_dir($source)) {
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

                foreach ($files as $file) {
                    $file = \str_replace('\\', '/', $file);

                    /* Ignore . and .. */
                    if (($pos = \mb_strrpos($file, '/')) === false
                        || \in_array(\mb_substr($file, $pos + 1), ['.', '..'])
                    ) {
                        continue;
                    }

                    $absolute = \realpath($file);
                    $absolute = \str_replace('\\', '/', (string) $absolute);
                    $dir      = \str_replace($source . '/', '', $relative . '/' . $absolute);

                    if (\is_dir($absolute)) {
                        $tar->addEmptyDir($dir . '/');
                    } elseif (\is_file($absolute)) {
                        $tar->addFile($absolute, $dir);
                    }
                }
            } elseif (\is_file($source)) {
                $tar->addFile($source, $relative);
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function unpack(string $source, string $destination) : bool
    {
        if (!\file_exists($source)) {
            return false;
        }

        $destination = \str_replace('\\', '/', $destination);
        $destination = \rtrim($destination, '/');
        $tar         = new \PharData($destination);

        $tar->extractTo($destination . '/');

        return true;
    }
}
