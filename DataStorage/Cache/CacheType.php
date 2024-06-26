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
 * Cache type enum.
 *
 * Cache types that are supported by the application
 *
 * @package phpOMS\DataStorage\Cache
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class CacheType extends Enum
{
    public const FILE = 'file';

    public const MEMCACHED = 'mem';

    public const REDIS = 'redis';

    public const UNDEFINED = 'na';
}
