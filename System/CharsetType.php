<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\System
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System;

use phpOMS\Stdlib\Base\Enum;

/**
 * Charset enum.
 *
 * @package phpOMS\System
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class CharsetType extends Enum
{
    public const ASCII = 'us-ascii';

    public const ISO_8859_1 = 'iso-8859-1';

    public const UTF_8 = 'utf-8';
}
