<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\Shipping\TNT
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Shipping\TNT;

use phpOMS\Api\Shipping\ShippingInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;

/**
 * Shipment api.
 *
 * @package phpOMS\Api\Shipping\TNT
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     https://express.tnt.com/expresswebservices-website/app/landing.html
 * @since   1.0.0
 */
final class TNTShipping implements ShippingInterface
{
}