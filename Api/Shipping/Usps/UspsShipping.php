<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\Shipping\Usps
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Shipping\Usps;

use phpOMS\Api\Shipping\ShippingInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;

/**
 * Shipment api.
 *
 * @package phpOMS\Api\Shipping\Usps
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     https://developer.usps.com/apis
 * @since   1.0.0
 */
final class UspsShipping implements ShippingInterface
{
}