<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Api\Shipping
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Shipping;

/**
 * Shipping factory.
 *
 * @package phpOMS\Api\Shipping
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ShippingFactory
{
    /**
     * Create shipping instance.
     *
     * @param int $type Shipping type
     *
     * @return ShippingInterface
     *
     * @throws \Exception This exception is thrown if the shipping type is not supported
     *
     * @since 1.0.0
     */
    public static function create(int $type) : ShippingInterface
    {
        switch ($type) {
            case ShippingType::DHL:
                return new \phpOMS\Api\Shipping\DHL\DHLInternationalShipping();
            case ShippingType::DPD:
                return new \phpOMS\Api\Shipping\DPD\DPDShipping();
            case ShippingType::FEDEX:
                return new \phpOMS\Api\Shipping\Fedex\FedexShipping();
            case ShippingType::ROYALMAIL:
                return new \phpOMS\Api\Shipping\RoyalMail\RoyalMailShipping();
            case ShippingType::TNT:
                return new \phpOMS\Api\Shipping\TNT\TNTShipping();
            case ShippingType::UPS:
                return new \phpOMS\Api\Shipping\UPS\UPSShipping();
            case ShippingType::USPS:
                return new \phpOMS\Api\Shipping\Usps\UspsShipping();
            default:
                throw new \Exception('Unsupported shipping type.');
        }
    }
}
