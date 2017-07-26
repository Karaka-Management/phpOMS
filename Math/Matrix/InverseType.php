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

namespace phpOMS\Math\Matrix;

use phpOMS\Datatypes\Enum;

/**
 * Inverse type enum.
 *
 * @category   Framework
 * @package    phpOMS\Math\Matrix
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class InverseType extends Enum
{
    /* public */ const GAUSS_JORDAN = 0;
}
