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
 * File size type enum.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class FileSizeType extends Enum
{
    const TERRABYTE = 'TB';
    const GIGABYTE = 'GB';
    const MEGABYTE = 'MB';
    const KILOBYTE = 'KB';
    const BYTE = 'B';
    const TERRABIT = 'tbit';
    const GIGABIT = 'gbit';
    const MEGABIT = 'mbit';
    const KILOBIT = 'kbit';
    const BIT = 'bit';
}
