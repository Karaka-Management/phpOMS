<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\Shipping
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Shipping;

use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;

/**
 * Shipping interface.
 *
 * For authentication there are usually 3 options depending on the service
 *  1. No user interaction: Store login+password in database or code and perform authentication via login+password and receive an access token
 *  2. No user interaction: Store api key or secret token in database and perform authentication via key/secret and receive an access token
 *  3. User interaction: Redirect to 3rd party login page. User performs manual login. 3rd party page redirects pack to own app after login incl. an access token
 *
 * @package phpOMS\Api\Shipping
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @todo implement Sender, Receiver, Package, Transit, Tracking classes for better type hinting instead of arrays
 *
 * @property string    $ENV ('live' = live environment, 'test' or 'sandbox' = test environment)
 * @property string    $client
 * @property string    $token
 * @property string    $refreshToken
 * @property string    $apiKey
 * @property string    $login
 * @property string    $password
 * @property \DateTime $expire
 * @property \DateTime $refreshExpire
 */
interface ShippingInterface
{
    /**
     * Create request for authentication using login and passowrd
     *
     * @param string $login    Login name/email
     * @param string $password Password
     * @param string $client   Client id
     * @param array  $payload  Other payload data
     *
     * @return int Returns auth status
     *
     * @since 1.0.0
     */
    public function authLogin(
        string $login, string $password,
        string $client = null,
        string $payload = null
    ) : int;

    /**
     * Create request for manual (user has to use form on external website) authentication.
     *
     * Creates a request object that redirects to a login page where the user has to enter
     * the login credentials. After login the external login page redirects back to the
     * redirect url which will also have a parameter containing the authentication token.
     *
     * Use tokenFromRedirect() to parse the token from the redirect after successful login.
     *
     * @param string      $client   Client information (e.g. client id)
     * @param null|string $redirect Redirect page after successfull login
     * @param array       $payload  Other payload data
     *
     * @return HttpRequest Request which should be used to create the redirect (e.g. header("Location: $request->uri"))
     *
     * @see authLogin() for services that require login+password
     * @see authApiKey() for services that require api key
     *
     * @since 1.0.0
     */
    public function authRedirectLogin(
        string $client,
        string $redirect = null,
        array $payload = []
    ) : HttpRequest;

    /**
     * Parses the redirect code after using authRedirectLogin() and creates a token from that code.
     *
     * @param string      $login    Login name/email
     * @param string      $password Password
     * @param HttpRequest $redirect Redirect request after the user successfully logged in.
     *
     * @return int Returns auth status
     *
     * @see authRedirectLogin()
     *
     * @since 1.0.0
     */
    public function tokenFromRedirect(
        string $login, string $password,
        HttpRequest $redirect
    ) : int;

    /**
     * Connect to API
     *
     * @param string $key Api key/permanent token
     *
     * @return int Returns auth status
     *
     * @since 1.0.0
     */
    public function authApiKey(string $key) : int;

    /**
     * Refreshes token using a refresh token
     *
     * @return int Returns auth status
     *
     * @since 1.0.0
     */
    public function refreshToken() : int;

    /**
     * Create shipment.
     *
     * @param array $sender   Sender
     * @param array $shipFrom Ship from location (sometimes sender != pickup location)
     * @param array $recevier Receiver
     * @param array $package  Package
     * @param array $data     Shipping data
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function ship(
        array $sender,
        array $shipFrom,
        array $receiver,
        array $package,
        array $data
    ) : array;

    /**
     * Cancel shipment.
     *
     * @param string $shipment   Shipment id
     * @param string[] $packages Packed ids (if a shipment consists of multiple packages)
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function cancel(string $shipment, array $packages = []) : bool;

    /**
     * Track shipment.
     *
     * @param string $shipment Shipment id
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function track(string $shipment) : array;
}
