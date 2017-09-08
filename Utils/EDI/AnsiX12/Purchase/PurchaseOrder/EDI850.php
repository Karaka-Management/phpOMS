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

namespace phpOMS\Utils\EDI\AnsiX12\Purchase\PurchaseOrder;

use phpOMS\Utils\EDI\AnsiX12\EDIAbstract;

/**
 * EDI 850 - Purchase order.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class EDI850 extends EDIAbstract
{
    public function __construct()
    {
        parent::__construct();
        $this->heading = new EDIT850Heading();
        $this->detail = new EDIT850Detail();
        $this->summary = new EDI850Summary();
    }
}
