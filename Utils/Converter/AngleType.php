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
namespace phpOMS\Utils\Converter;

use phpOMS\Datatypes\Enum;

/**
 * Speed type enum.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class AngleType extends Enum
{
    const DEGREE = 'º';
    const RADIAN = 'rad';
    const SECOND = 'arcsec';
    const MINUTE = 'arcmin';
    const MILLIRADIAN_US = 'mil (us ww2)';
    const MILLIRADIAN_UK = 'mil (uk)';
    const MILLIRADIAN_USSR = 'mil (ussr)';
    const MILLIRADIAN_NATO = 'mil (nato)';
    const GRADIAN = 'g';
    const CENTRAD = 'crad';
}
