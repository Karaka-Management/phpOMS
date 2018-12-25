<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils\Barcode
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\Barcode;

use phpOMS\Stdlib\Base\Enum;

/**
 * Orientation type enum.
 *
 * @package    phpOMS\Utils\Barcode
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class OrientationType extends Enum
{
    public const HORIZONTAL = 0;
    public const VERTICAL   = 1;
}
