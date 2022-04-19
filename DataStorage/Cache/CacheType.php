<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Cache
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
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
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class CacheType extends Enum
{
    public const FILE = 'file';

    public const MEMCACHED = 'mem';

    public const REDIS = 'redis';

    public const UNDEFINED = 'na';
}
