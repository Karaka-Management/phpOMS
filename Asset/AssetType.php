<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Asset
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Asset;

use phpOMS\Stdlib\Base\Enum;

/**
 * Asset types enum.
 *
 * @package phpOMS\Asset
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class AssetType extends Enum
{
    public const CSS = 0;

    public const JS = 1;

    public const JSLATE = 2;
}
