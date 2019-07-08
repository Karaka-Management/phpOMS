<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\File
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\File;

use phpOMS\Stdlib\Base\Enum;

/**
 * Query type enum.
 *
 * @package    phpOMS\DataStorage\File
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class QueryType extends Enum
{
    public const SELECT = 0;
    public const INSERT = 1;
    public const UPDATE = 2;
    public const DELETE = 3;
    public const RANDOM = 4;
}
