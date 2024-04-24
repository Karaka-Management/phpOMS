<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Api\Shipping\DHL
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Shipping\DHL;

use phpOMS\Api\Shipping\AuthStatus;
use phpOMS\Api\Shipping\ShippingInterface;
use phpOMS\Message\Http\HttpRequest;

/**
 * Shipment api.
 *
 * @package phpOMS\Api\Shipping\DHL
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     General: https://developer.dhl.com/
 * @see     Special: https://developer.dhl.com/api-reference/ecommerce-europe#get-started-section/
 * @see     Tracking: https://developer.dhl.com/api-reference/shipment-tracking#get-started-section/
 * @since   1.0.0
 */
final class DHLeCommerceShipping implements ShippingInterface
{
    /**
     * {@inheritdoc}
     */
    public function authLogin(
        string $login, string $password,
        ?string $client = null,
        ?string $payload = null
    ) : int
    {
        return AuthStatus::FAILED;
    }

    /**
     * {@inheritdoc}
     */
    public function authRedirectLogin(
        string $client,
        ?string $redirect = null,
        array $payload = []
    ) : HttpRequest
    {
        return new HttpRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function tokenFromRedirect(
        string $login, string $password,
        HttpRequest $redirect
    ) : int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function authApiKey(string $key) : int
    {
        return AuthStatus::FAILED;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshToken() : int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function ship(
        array $sender,
        array $shipFrom,
        array $receiver,
        array $package,
        array $data
    ) : array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function cancel(string $shipment, array $packages = []) : bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function track(string $shipment) : array
    {
        return [];
    }
}
