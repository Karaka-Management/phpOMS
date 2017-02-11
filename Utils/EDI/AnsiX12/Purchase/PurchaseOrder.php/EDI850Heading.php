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

namespace phpOMS\Utils\EDI\AnsiX12\Purchase\PurchaseOrder;

/**
 * EDI 850 - Purchase order.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class EDI850Heading
{
    private $headingTransactionSetHeader = '';

    private $headingBeginningSegmentPO = '';

    private $headingCurrency = '';

    private $headingReferenceID = '';

    private $headingAdministrativeCommunicationsContact = '';

    private $headingDateTimeReference = null;

    private $headignCarrierDetails = '';

    private $headingMarksNumbers = 0;

    private $headingLoopId = [];
}
