<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
use phpOMS\Api\Shipping\AuthType;
use phpOMS\Api\Shipping\ShippingInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\System\MimeType;
use phpOMS\Uri\HttpUri;

/**
 * Shipment API.
 *
 * In the third party API the following definitions are important to know:
 *
 *  1. Order: A collection of shipments with the same service (standard, return, packet plus, packet tracked)
 *  2. Item: A shipment/package
 *
 * @package phpOMS\Api\Shipping\DHL
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     General: https://developer.dhl.com/
 * @see     Special: https://developer.dhl.com/api-reference/deutsche-post-international-post-parcel-germany#get-started-section/
 * @see     Tracking: https://developer.dhl.com/api-reference/shipment-tracking#get-started-section/
 * @since   1.0.0
 */
final class DHLInternationalShipping implements ShippingInterface
{
    /**
     * Api version
     *
     * @var string
     * @since 1.0.0
     */
    public const API_VERSION = 'v1';

    /**
     * API environment.
     *
     * @var string
     * @since 1.0.0
     */
    public static string $ENV = 'live';

    /**
     * API link to live/production version.
     *
     * @var string
     * @since 1.0.0
     */
    public const LIVE_URL = 'https://api-eu.dhl.com/dpi';

    /**
     * API link to test/sandbox version.
     *
     * @var string
     * @since 1.0.0
     */
    public const SANDBOX_URL = 'https://api-sandbox.dhl.com/dpi';

    /**
     * API link to test/sandbox version.
     *
     * This implementation uses different testing urls for the different endpoints
     *
     * @var string
     * @since 1.0.0
     */
    public const SANDBOX2_URL = 'https://api-test.dhl.com';

    /**
     * The type of authentication that is supported.
     *
     * @var int
     * @since 1.0.0
     */
    public const AUTH_TYPE = AuthType::AUTOMATIC_LOGIN | AuthType::KEY_LOGIN;

    /**
     * Minimum auth expiration time until re-auth.
     *
     * @var int
     * @since 1.0.0
     */
    public const TIME_DELTA = 10;

    /**
     * Client id
     *
     * @var string
     * @since 1.0.0
     */
    public string $client = '';

    /**
     * Login id
     *
     * @var string
     * @since 1.0.0
     */
    public string $login = '';

    /**
     * Password
     *
     * @var string
     * @since 1.0.0
     */
    public string $password = '';

    /**
     * Current auth token
     *
     * @var string
     * @since 1.0.0
     */
    public string $token = '';

    /**
     * Current auth refresh token in case the token expires
     *
     * @var string
     * @since 1.0.0
     */
    public string $refreshToken = '';

    /**
     * Api Key
     *
     * @var string
     * @since 1.0.0
     */
    public string $apiKey = '';

    /**
     * Token expiration.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    public \DateTime $expire;

    /**
     * Refresh token expiration.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    public \DateTime $refreshExpire;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->expire        = new \DateTime('now');
        $this->refreshExpire = new \DateTime('now');
    }

    /**
     * {@inheritdoc}
     */
    public function authLogin(
        string $login, string $password,
        string $client = null,
        string $payload = null
    ) : int
    {
        $this->client   = $client ?? $this->client;
        $this->login    = $login;
        $this->password = $password;

        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/' . self::API_VERSION . '/auth/accesstoken';

        $request = new HttpRequest(new HttpUri($uri));
        $request->setMethod(RequestMethod::GET);

        $request->header->set('Content-Type', MimeType::M_JSON);
        $request->header->set('Accept', '*/*');
        $request->header->set('Authorization', 'Basic ' . \base64_encode($login . ':' . $password));

        $this->expire = new \DateTime('now');

        $response = Rest::request($request);

        switch ($response->header->status) {
            case 400:
            case 401:
                $status = AuthStatus::FAILED;
                break;
            case 403:
                $status = AuthStatus::BLOCKED;
                break;
            case 429:
                $status = AuthStatus::LIMIT_EXCEEDED;
                break;
            case 200:
                $this->token = $response->getData('access_token') ?? '';
                $this->expire->setTimestamp($this->expire->getTimestamp() + ((int) $response->getData('expires_in')));

                $status = AuthStatus::OK;
                break;
            default:
                $status = AuthStatus::FAILED;
        }

        return $status;
    }

    /**
     * {@inheritdoc}
     */
    public function authRedirectLogin(
        string $client,
        string $redirect = null,
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
        return AuthStatus::FAILED;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshToken() : int
    {
        return AuthStatus::FAILED;
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
    public function ship(
        array $sender,
        array $shipFrom,
        array $receiver,
        array $package,
        array $data
    ) : array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function cancel(string $shipment, array $packages = []) : bool
    {
    }

    /**
     * {@inheritdoc}
     */
    public function track(string $shipment) : array
    {
        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX2_URL;
        $uri  = $base . '/track/shipments';

        $httpUri = new HttpUri($uri);
        $httpUri->addQuery('trackingnumber', $shipment);
        $httpUri->addQuery('limit', 10);

        // @todo implement: express, parcel-de, ecommerce, dgf, parcel-uk, post-de, sameday, freight, parcel-nl, parcel-pl, dsc, ecommerce-europe, svb
        //$httpUri->addQuery('service', '');

        // @odo: implement
        //$httpUri->addQuery('requesterCountryCode', '');
        //$httpUri->addQuery('originCountryCode', '');
        //$httpUri->addQuery('recipientPostalCode', '');

        $request = new HttpRequest($httpUri);

        $request->setMethod(RequestMethod::GET);
        $request->header->set('accept', MimeType::M_JSON);
        $request->header->set('dhl-api-key', $this->apiKey);

        $response = Rest::request($request);
        if ($response->header->status !== 200) {
            return [];
        }

        $shipments = $response->getDataArray('shipments') ?? [];
        $tracking  = [];

        // @todo add general shipment status (not just for individual packages)

        foreach ($shipments as $shipment) {
            $packages = [];
            $package  = $shipment;

            $activities = [];
            foreach ($package['events'] as $activity) {
                $activities[] = [
                    'date'        => new \DateTime($activity['timestamp']),
                    'description' => $activity['description'],
                    'location'    => [
                        'address' => [
                            $activity['location']['address']['streetAddress'],
                            $activity['location']['address']['addressLocality'],
                        ],
                        'city'         => '',
                        'country'      => '',
                        'country_code' => $activity['location']['address']['countryCode'],
                        'zip'          => $activity['location']['address']['postalCode'],
                        'state'        => '',
                    ],
                    'status' => [
                        'code'        => $activity['statusCode'],
                        'statusCode'  => $activity['statusCode'],
                        'description' => $activity['status'],
                    ],
                ];
            }

            $packages[] = [
                'status'   => [
                    'code'        => $package['status']['statusCode'],
                    'statusCode'  => $package['status']['statusCode'],
                    'description' => $package['status']['status'],
                ],
                'deliveryDate'        => new \DateTime($package['estimatedTimeOfDelivery']),
                'count'               => $package['details']['totalNumberOfPieces'],
                'weight'              => $package['details']['weight']['weight'],
                'weight_unit'         => 'g',
                'activities'          => $activities,
                'received'            => [
                    'by'        => $package['details']['proofOfDelivery']['familyName'],
                    'signature' => $package['details']['proofOfDelivery']['signatureUrl'],
                    'location'  => '',
                    'date'      => $package['details']['proofOfDelivery']['timestamp'],
                ],
            ];

            $tracking[] = $packages;
        }

        return $tracking;
    }

    /**
     * Get label information for shipment
     *
     * @param string $shipment Shipment id or token
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function label(string $shipment) : array
    {
        return [];
    }

    /**
     * Finalize shipments (no further changes possible)
     *
     * @param string[] $shipment Shipments to finalize
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function finalize(array $shipment = []) : bool
    {
        return true;
    }
}
