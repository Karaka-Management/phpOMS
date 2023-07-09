<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\IO\Zip
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
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
 * @package phpOMS\Utils\IO\Zip
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Zip implements ArchiveInterface
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

        $zip = new \ZipArchive();
        if (!$zip->open($destination, $overwrite ? \ZipArchive::CREATE | \ZipArchive::OVERWRITE : \ZipArchive::CREATE)) {
            return false; // @codeCoverageIgnore
        }

        if (\is_string($sources)) {
            $sources = [$sources => ''];
        }

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
                        $zip->addEmptyDir($dir . '/');
                    } elseif (\is_file($absolute)) {
                        $zip->addFile($absolute, $dir);
                    }
                }
            } elseif (\is_file($source)) {
                $zip->addFile($source, $relative);
            } else {
                continue;
            }
        }

        return $zip->close();
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

        $destination = \str_replace('\\', '/', $destination);
        $destination = \rtrim($destination, '/');

        try {
            $zip = new \ZipArchive();
            if (!$zip->open($source)) {
                return false; // @codeCoverageIgnore
            }

            $zip->extractTo($destination . '/');

            return $zip->close();
        } catch (\Throwable $_) {
            return false;
        }
    }
}
