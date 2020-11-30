<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils\IO\Zip
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Zip;

use phpOMS\System\File\FileUtils;

/**
 * Zip class for handling zip files.
 *
 * Providing basic zip support
 *
 * @package phpOMS\Utils\IO\Zip
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Tar implements ArchiveInterface
{
    /**
     * {@inheritdoc}
     */
    public static function pack($sources, string $destination, bool $overwrite = false) : bool
    {
        $destination = FileUtils::absolute(\str_replace('\\', '/', $destination));

        if (!$overwrite && \is_file($destination)) {
            return false;
        }

        $tar = new \PharData($destination);

        /**
         * @var string $relative
         */
        foreach ($sources as $source => $relative) {
            if (\is_int($source)) {
                $source = $relative;
            }

            $source   = \str_replace('\\', '/', $source);
            $relative = \str_replace('\\', '/', $relative);

            if (\is_dir($source)) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($source, \FilesystemIterator::CURRENT_AS_PATHNAME),
                    \RecursiveIteratorIterator::SELF_FIRST
                );

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
                    $dir      = \rtrim($relative, '/\\') . '/' . \ltrim(\str_replace($source . '/', '', $absolute), '/\\');

                    if (\is_dir($absolute)) {
                        $tar->addEmptyDir($dir . '/');
                    } elseif (\is_file($absolute)) {
                        $tar->addFile($absolute, $dir);
                    }
                }
            } elseif (\is_file($source)) {
                $tar->addFile($source, $relative);
            } else {
                continue;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function unpack(string $source, string $destination) : bool
    {
        if (!\is_file($source)) {
            return false;
        }

        try {
            $destination = \str_replace('\\', '/', $destination);
            $destination = \rtrim($destination, '/');
            $tar         = new \PharData($source);

            $tar->extractTo($destination . '/');

            return true;
        } catch (\Throwable $t) {
            return false;
        }
    }
}
