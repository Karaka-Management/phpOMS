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

namespace phpOMS\Utils\RnG;

use phpOMS\Datatypes\Enum;

/**
 * Distribution type enum.
 *
 * @category   Framework
 * @package    Utils/RnG
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class DistributionType extends Enum
{
    /* public */ const UNIFORM = 0;

    /* public */ const NORMAL = 1;
}
