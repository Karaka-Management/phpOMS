<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */

namespace phpOMS\Math\Number;

use phpOMS\Datatypes\Enum;

/**
 * Number type enum.
 *
 * @category   Framework
 * @package    Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class AccountType extends Enum
{
    const INTEGER = 1;
    const NATURAL = 21;
    const EVEN = 211;
    const UNEVEN = 212;
    const PRIME = 22;
    const REAL = 3;
    const RATIONAL = 4;
    const IRRATIONAL = 5;
    const COMPLEX = 6;
}