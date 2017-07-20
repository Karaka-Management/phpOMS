<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Utils\Converter;

use phpOMS\Datatypes\Enum;

/**
 * File size type enum.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class FileSizeType extends Enum
{
    /* public */ const TERRABYTE = 'TB';
    /* public */ const GIGABYTE = 'GB';
    /* public */ const MEGABYTE = 'MB';
    /* public */ const KILOBYTE = 'KB';
    /* public */ const BYTE = 'B';
    /* public */ const TERRABIT = 'tbit';
    /* public */ const GIGABIT = 'gbit';
    /* public */ const MEGABIT = 'mbit';
    /* public */ const KILOBIT = 'kbit';
    /* public */ const BIT = 'bit';
}
