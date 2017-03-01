<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\System\File;

use phpOMS\Datatypes\Enum;

/**
 * Database type enum.
 *
 * Database types that are supported by the application
 *
 * @category   Framework
 * @package    phpOMS\System
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class ExtensionType extends Enum
{
    /* public */ const UNKNOWN = 1;
    /* public */ const CODE = 2;
    /* public */ const AUDIO = 4;
    /* public */ const VIDEO = 8;
    /* public */ const TEXT = 16;
    /* public */ const SPREADSHEET = 32;
    /* public */ const PDF = 64;
    /* public */ const ARCHIVE = 128;
    /* public */ const PRESENTATION = 256;
    /* public */ const IMAGE = 512;
}