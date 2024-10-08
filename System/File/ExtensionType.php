<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\System\File
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System\File;

use phpOMS\Stdlib\Base\Enum;

/**
 * Extension type enum.
 *
 * Defines what kind of category a file belongs to.
 *
 * @package phpOMS\System\File
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class ExtensionType extends Enum
{
    public const UNKNOWN = 1;

    public const CODE = 2;

    public const AUDIO = 4;

    public const VIDEO = 8;

    public const TEXT = 16;

    public const SPREADSHEET = 32;

    public const PDF = 64;

    public const ARCHIVE = 128;

    public const PRESENTATION = 256;

    public const IMAGE = 512;

    public const EXECUTABLE = 1024;

    public const DIRECTORY = 2048;

    public const WORD = 4096;

    public const REFERENCE = 8192;
}
