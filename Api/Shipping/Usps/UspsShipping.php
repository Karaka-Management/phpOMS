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

use phpOMS\Api\Shipping\AuthStatus;
use phpOMS\Api\Shipping\ShippingInterface;
use phpOMS\Message\Http\HttpRequest;

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
