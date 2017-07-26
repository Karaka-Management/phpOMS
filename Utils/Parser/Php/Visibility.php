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

use phpOMS\Datatypes\Enum;

/**
 * Visibility type enum.
 *
 * Visibility for member variables and functions
 *
 * @category   Framework
 * @package    phpOMS\Utils\Parser
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class Visibility extends Enum
{
    /* public */ const _NONE = '';
    /* public */ const _PUBLIC = 'public';
    /* public */ const _PRIVATE = 'private';
    /* public */ const _PROTECTED = 'protected';
}
