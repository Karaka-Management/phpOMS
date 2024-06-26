<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Cache
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache;

use phpOMS\Stdlib\Base\Enum;

/**
 * Cache status enum.
 *
 * Possible caching status
 *
 * @package phpOMS\DataStorage\Cache
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class CacheStatus extends Enum
{
    public const OK = 0;

    public const FAILURE = 1;

    public const READONLY = 2;

    public const CLOSED = 3;
}
