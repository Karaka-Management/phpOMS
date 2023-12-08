<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\Shipping\DPD
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Shipping\DPD;

use phpOMS\Api\Shipping\ShippingInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;

/**
 * Shipment api.
 *
 * @package phpOMS\Api\Shipping\DPD
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     https://api.dpd.ro/web-api.html#href-create-shipment-req
 * @see     https://www.dpd.com/de/en/entwickler-integration-in-ihre-versandloesung/
 * @since   1.0.0
 */
final class DPDShipping implements ShippingInterface
{
}