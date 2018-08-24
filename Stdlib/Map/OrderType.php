<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Stdlib\Map
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Map;

use phpOMS\Stdlib\Base\Enum;

/**
 * Account type enum.
 *
 * @package    phpOMS\Stdlib\Map
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class OrderType extends Enum
{
    public const LOOSE  = 0;
    public const STRICT = 1;
}
