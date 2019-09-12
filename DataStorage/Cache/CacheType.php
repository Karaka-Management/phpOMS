<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage\Cache
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
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
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class CacheType extends Enum
{
    public const FILE      = 'file';
    public const MEMCACHED = 'mem';
    public const REDIS     = 'redis';
    public const UNDEFINED = 'na';
}
