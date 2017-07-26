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

namespace phpOMS\Math\Number;

use phpOMS\Datatypes\Enum;

/**
 * Number type enum.
 *
 * @category   Framework
 * @package    Utils
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class NumberType extends Enum
{
    /* public */ const INTEGER = 1;
    /* public */ const NATURAL = 21;
    /* public */ const EVEN = 211;
    /* public */ const UNEVEN = 212;
    /* public */ const PRIME = 22;
    /* public */ const REAL = 3;
    /* public */ const RATIONAL = 4;
    /* public */ const IRRATIONAL = 5;
    /* public */ const COMPLEX = 6;
}