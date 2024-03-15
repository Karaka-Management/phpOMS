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
final class Gz implements ArchiveInterface
{
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
     * {@inheritdoc}
     */
    public static function pack(string | array $source, string $destination, bool $overwrite = false) : bool
    {
        $destination = \strtr($destination, '\\', '/');
        if ($destination === false
            || \is_array($source)
            || (!$overwrite && \is_file($destination))
            || !\is_file($source)
        ) {
            return false;
        }

        $gz  = \gzopen($destination, 'w');
        $src = \fopen($source, 'r');
        if ($gz === false || $src === false) {
            return false; // @codeCoverageIgnore
        }

        while (!\feof($src)) {
            $read = \fread($src, 4096);
            \gzwrite($gz, $read === false ? '' : $read);
        }

        \fclose($src);

        return \gzclose($gz);
    }

    /**
     * {@inheritdoc}
     */
    public static function unpack(string $source, string $destination) : bool
    {
        $destination = \strtr($destination, '\\', '/');
        if (\is_file($destination) || !\is_file($source)) {
            return false;
        }

        $gz   = \gzopen($source, 'r');
        $dest = \fopen($destination, 'w');
        if ($gz === false || $dest === false) {
            return false; // @codeCoverageIgnore
        }

        while (!\gzeof($gz) && ($read = \gzread($gz, 4096)) !== false) {
            \fwrite($dest, $read);
        }

        \fclose($dest);

        return \gzclose($gz);
    }
}
