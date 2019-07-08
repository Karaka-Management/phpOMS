<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Account
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Account;

use phpOMS\Stdlib\Base\Enum;

/**
 * Group status enum.
 *
 * @package    phpOMS\Account
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class GroupStatus extends Enum
{
    public const ACTIVE   = 1;
    public const INACTIVE = 2;
    public const HIDDEN   = 4;
}
