<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Php;

use phpOMS\Stdlib\Base\Enum;

/**
 * Database type enum.
 *
 * Database types that are supported by the application
 *
 * @category   Framework
 * @package    phpOMS\Utils\Parser
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class ClassType extends Enum
{
    /* public */ const _CLASS = 'class';
    /* public */ const _TRAIT = 'trait';
    /* public */ const _INTERFACE = 'interface';
}
