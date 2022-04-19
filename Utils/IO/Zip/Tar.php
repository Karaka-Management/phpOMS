<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\IO\Zip
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Zip;

use phpOMS\System\File\FileUtils;
use phpOMS\System\File\Local\Directory;

/**
 * Zip class for handling zip files.
 *
 * Providing basic zip support
 *
 * IMPORTANT:
 * PharData seems to cache created files, which means even if the previously created file is deleted, it cannot create a new file with the same destination.
 * bug? https://bugs.php.net/bug.php?id=75101
 *
 * @package phpOMS\Utils\IO\Zip
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class Tar implements ArchiveInterface
{
    /**
     * {@inheritdoc}
     */
    public static function pack(string | array $sources, string $destination, bool $overwrite = false) : bool
    {
        $destination = FileUtils::absolute(\str_replace('\\', '/', $destination));
        if ((!$overwrite && \is_file($destination))
            || \is_dir($destination)
        ) {
            return false;
        }

        if (\is_string($sources)) {
            $sources = [$sources => ''];
        }

        $tar = new \PharData($destination);

        /**
         * @var string $relative
         */
        foreach ($sources as $source => $relative) {
            if (\is_int($source)) {
                $source = $relative;
            }

            $source   = FileUtils::absolute(\str_replace('\\', '/', $source));
            $relative = \str_replace('\\', '/', $relative);

            if (\is_dir($source)) {
                /** @var string[] $files */
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
                    $dir      = \ltrim(\rtrim($relative, '/\\') . '/' . \ltrim(\str_replace($source . '/', '', $absolute), '/\\'), '/\\');

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

        if (!\is_dir($destination)) {
            Directory::create($destination, recursive: true);
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
