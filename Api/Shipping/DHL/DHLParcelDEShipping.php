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
use phpOMS\Localization\ISO3166CharEnum;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\System\MimeType;
use phpOMS\Uri\HttpUri;

/**
 * Shipment API.
 *
 * @package phpOMS\Api\Shipping\DHL
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     General: https://developer.dhl.com/
 * @see     Special: https://developer.dhl.com/api-reference/parcel-de-shipping-post-parcel-germany-v2#get-started-section/
 * @see     Tracking: https://developer.dhl.com/api-reference/shipment-tracking#get-started-section/
 * @since   1.0.0
 */
final class DHLParcelDEShipping implements ShippingInterface
{
    /**
     * Api version
     *
     * @var string
     * @since 1.0.0
     */
    public const API_VERSION = 'v2';

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
    public const LIVE_URL = 'https://api-eu.dhl.com';

    /**
     * API link to test/sandbox version.
     *
     * @var string
     * @since 1.0.0
     */
    public const SANDBOX_URL = 'https://api-sandbox.dhl.com';

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
        ?string $client = null,
        ?string $payload = null
    ) : int
    {
        $this->apiKey   = $client ?? $this->client;
        $this->login    = $login;
        $this->password = $password;

        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/parcel/de/shipping/' . self::API_VERSION;

        $request = new HttpRequest(new HttpUri($uri));
        $request->setMethod(RequestMethod::GET);
        $request->header->set('Authorization', 'Basic ' . \base64_encode($this->login . ':' . $this->password));
        $request->header->set('dhl-api-key', $this->apiKey);

        $this->expire = new \DateTime('now');

        $response = Rest::request($request);

        switch ($response->header->status) {
            case 400:
            case 500:
                $status = AuthStatus::FAILED;
                break;
            case 403:
                $status = AuthStatus::BLOCKED;
                break;
            case 429:
                $status = AuthStatus::LIMIT_EXCEEDED;
                break;
            case 200:
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
        $this->apiKey = $key;

        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/parcel/de/shipping/' . self::API_VERSION;

        $request = new HttpRequest(new HttpUri($uri));
        $request->setMethod(RequestMethod::GET);
        $request->header->set('Accept', MimeType::M_JSON);
        $request->header->set('dhl-api-key', $key);

        $response = Rest::request($request);

        switch ($response->header->status) {
            case 400:
            case 500:
                $status = AuthStatus::FAILED;
                break;
            case 403:
                $status = AuthStatus::BLOCKED;
                break;
            case 429:
                $status = AuthStatus::LIMIT_EXCEEDED;
                break;
            case 200:
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
    public function ship(
        array $sender,
        array $shipFrom,
        array $receiver,
        array $package,
        array $data
    ) : array
    {
        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/parcel/de/shipping/' . self::API_VERSION .'/orders';

        $httpUri = new HttpUri($uri);
        $httpUri->addQuery('validate', 'true');

        // @todo implement docFormat
        $httpUri->addQuery('docFormat', 'PDF');

        // @todo implement printFormat
        // Available values : A4, 910-300-600, 910-300-610, 910-300-700, 910-300-700-oz, 910-300-710, 910-300-300, 910-300-300-oz, 910-300-400, 910-300-410, 100x70mm
        // If not set, default specified in customer portal will be used
        // @todo implement as class setting
        //$request->setData('printFormat', '');

        $request = new HttpRequest($httpUri);
        $request->setMethod(RequestMethod::POST);
        $request->header->set('Content-Type', MimeType::M_JSON);
        $request->header->set('Accept-Language', 'en-US');
        $request->header->set('Authorization', 'Basic ' . \base64_encode($this->login . ':' . $this->password));

        $request->setData('STANDARD_GRUPPENPROFIL', 'PDF');

        $shipments = [
            [
                'product'       => 'V01PAK', // V53WPAK, V53WPAK
                'billingNumber' => $data['costcenter'], // @todo maybe dhl number, check
                'refNo'         => $package['id'],
                'shipper'       => [
                    'name1'                         => $sender['name'],
                    'addressStreet'                 => $sender['address'],
                    'additionalAddressInformation1' => $sender['address_addition'],
                    'postalCode'                    => $sender['zip'],
                    'city'                          => $sender['city'],
                    'country'                       => ISO3166CharEnum::getBy2Code($sender['country_code']),
                    'email'                         => $sender['email'],
                    'phone'                         => $sender['phone'],
                ],
                'consignee' => [
                    'name1'                         => $receiver['name'],
                    'addressStreet'                 => $receiver['address'],
                    'additionalAddressInformation1' => $receiver['address_addition'],
                    'postalCode'                    => $receiver['zip'],
                    'city'                          => $receiver['city'],
                    'country'                       => ISO3166CharEnum::getBy2Code($receiver['country_code']),
                    'email'                         => $receiver['email'],
                    'phone'                         => $receiver['phone'],
                ],
                'details' => [
                    'dim' => [
                        'uom'    => 'mm',
                        'height' => $package['height'],
                        'length' => $package['length'],
                        'width'  => $package['width'],
                    ],
                    'weight' => [
                        'uom'   => 'g',
                        'value' => $package['weight'],
                    ],
                ],
            ],
        ];

        $request->setData('shipments', $shipments);

        $response = Rest::request($request);
        if ($response->header->status !== 200) {
            return [];
        }

        $result = $response->getDataArray('items') ?? [];

        $labelUri = new HttpUri($result[0]['label']['url']);
        $label    = $this->label($labelUri->getQuery('token'));

        return [
            'id'    => $result[0]['shipmentNo'],
            'label' => [
                'code' => $result[0]['label']['format'],
                'url'  => $result[0]['label']['url'],
                'data' => $label['data'],
            ],
            'packages' => [
                'id'    => $result[0]['shipmentNo'],
                'label' => [
                    'code' => $result[0]['label']['format'],
                    'url'  => $result[0]['label']['url'],
                    'data' => $label['data'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function cancel(string $shipment, array $packages = []) : bool
    {
        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/parcel/de/shipping/' . self::API_VERSION .'/orders';

        $request = new HttpRequest(new HttpUri($uri));
        $request->setMethod(RequestMethod::DELETE);
        $request->header->set('Accept-Language', 'en-US');
        $request->header->set('Authorization', 'Basic ' . \base64_encode($this->login . ':' . $this->password));

        $request->setData('profile', 'STANDARD_GRUPPENPROFIL');
        $request->setData('shipment', $shipment);

        $response = Rest::request($request);

        return $response->header->status === 200;
    }

    /**
     * Get shipment information (no tracking)
     *
     * This includes depending on service labels, shipping documents and general shipment information.
     * For some services this function simply re-creates the data from ship().
     *
     * @param string $shipment Shipment id or token
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function info(string $shipment) : array
    {
        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/parcel/de/shipping/' . self::API_VERSION .'/orders';

        $httpUri = new HttpUri($uri);
        $httpUri->addQuery('shipment', $shipment);

        // @todo implement docFormat etc
        $httpUri->addQuery('docFormat', 'PDF');

        $request = new HttpRequest($httpUri);
        $request->setMethod(RequestMethod::GET);
        $request->header->set('Accept-Language', 'en-US');
        $request->header->set('Authorization', 'Basic ' . \base64_encode($this->login . ':' . $this->password));

        $response = Rest::request($request);
        if ($response->header->status !== 200) {
            return [];
        }

        $result = $response->getDataArray('items') ?? [];

        $labelUri = new HttpUri($result[0]['label']['url']);
        $label    = $this->label($labelUri->getQuery('token'));

        return [
            'id'    => $result[0]['shipmentNo'],
            'label' => [
                'code' => $result[0]['label']['format'],
                'url'  => $result[0]['label']['url'],
                'data' => $label['data'],
            ],
            'packages' => [
                'id'    => $result[0]['shipmentNo'],
                'label' => [
                    'code' => $result[0]['label']['format'],
                    'url'  => $result[0]['label']['url'],
                    'data' => $label['data'],
                ],
            ],
        ];
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
        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/parcel/de/shipping/' . self::API_VERSION .'/labels';

        $httpUri = new HttpUri($uri);
        $httpUri->addQuery('token', $shipment);

        $request = new HttpRequest($httpUri);
        $request->setMethod(RequestMethod::GET);
        $request->header->set('Content-Type', MimeType::M_PDF);

        $response = Rest::request($request);
        if ($response->header->status !== 200) {
            return [];
        }

        return [
            'data' => $response->getData(),
        ];
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
                'status' => [
                    'code'        => $package['status']['statusCode'],
                    'statusCode'  => $package['status']['statusCode'],
                    'description' => $package['status']['status'],
                ],
                'deliveryDate' => new \DateTime($package['estimatedTimeOfDelivery']),
                'count'        => $package['details']['totalNumberOfPieces'],
                'weight'       => $package['details']['weight']['weight'],
                'weight_unit'  => 'g',
                'activities'   => $activities,
                'received'     => [
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
     * Get daily manifest
     *
     * @param \DateTime $date Date of the manifest
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getManifest(?\DateTime $date = null) : array
    {
        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/parcel/de/shipping/' . self::API_VERSION .'/manifest';

        $httpUri = new HttpUri($uri);
        if ($date !== null) {
            $httpUri->addQuery('date', $date->format('Y-m-d'));
        }

        $request = new HttpRequest($httpUri);
        $request->setMethod(RequestMethod::GET);
        $request->header->set('Accept-Language', 'en-US');
        $request->header->set('Authorization', 'Basic ' . \base64_encode($this->login . ':' . $this->password));

        $response = Rest::request($request);
        if ($response->header->status !== 200) {
            return [];
        }

        return [
            'date'   => $response->getDataDateTime('manifestDate'),
            'b64'    => $response->getDataArray('manifest')['b64'],
            'zpl2'   => $response->getDataArray('manifest')['zpl2'],
            'url'    => $response->getDataArray('manifest')['url'],
            'format' => $response->getDataArray('manifest')['printFormat'],
        ];
    }

    /**
     * Finalize shipments.
     *
     * No further adjustments are possible.
     *
     * @param array $shipment Shipments to finalize. If empty = all
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function finalize(array $shipment = []) : bool
    {
        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/parcel/de/shipping/' . self::API_VERSION .'/manifest';

        $httpUri = new HttpUri($uri);
        $httpUri->addQuery('all', empty($shipment) ? 'true' : 'false');

        $request = new HttpRequest($httpUri);
        $request->setMethod(RequestMethod::POST);
        $request->header->set('Content-Type', MimeType::M_JSON);
        $request->header->set('Authorization', 'Basic ' . \base64_encode($this->login . ':' . $this->password));

        if (!empty($shipment)) {
            $request->setData('shipmentNumbers', $shipment);
        }

        $response = Rest::request($request);

        return $response->header->status === 200;
    }
}
