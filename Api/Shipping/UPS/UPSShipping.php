<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\Shipping\UPS
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Shipping\UPS;

use phpOMS\Api\Shipping\AuthStatus;
use phpOMS\Api\Shipping\AuthType;
use phpOMS\Api\Shipping\ShippingInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;

/**
 * Shipment API.
 *
 * @package phpOMS\Api\Shipping\UPS
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     https://developer.ups.com/api/reference/oauth/authorization-code?loc=en_US
 * @see     https://developer.ups.com/api/reference?loc=en_US
 * @since   1.0.0
 */
final class UPSShipping implements ShippingInterface
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
    public const LIVE_URL = 'https://onlinetools.ups.com';

    /**
     * API link to test/sandbox version.
     *
     * @var string
     * @since 1.0.0
     */
    public const SANDBOX_URL = 'https://wwwcie.ups.com';

    /**
     * The type of authentication that is supported.
     *
     * @var int
     * @since 1.0.0
     */
    public const AUTH_TYPE = AuthType::AUTOMATIC_LOGIN | AuthType::MANUAL_LOGIN;

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
     * Refresh token expiration.
     *
     * @var \DateTime
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
        $uri  = $base . '/security/' . self::API_VERSION . '/oauth/token';

        $request = new HttpRequest(new HttpUri($uri));
        $request->setMethod(RequestMethod::POST);
        $request->setData('grant_type', 'client_credentials');
        $request->header->set('Content-Type', 'application/x-www-form-urlencoded');
        $request->header->set('x-merchant-id', $client);
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
        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/security/' . self::API_VERSION . '/oauth/authorize';

        $request = new HttpRequest(new HttpUri($uri));

        $request->setMethod(RequestMethod::GET);
        $request->setData('client_id', $client);
        $request->setData('redirect_uri', $redirect);
        $request->setData('response_type', 'code');

        if (isset($payload['id'])) {
            $request->setData('scope', $payload['id']);
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function tokenFromRedirect(
        string $login, string $password,
        HttpRequest $redirect
    ) : int
    {
        $code = $redirect->getData('code') ?? '';

        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/security/' . self::API_VERSION . '/oauth/token';

        $request = new HttpRequest(new HttpUri($uri));
        $request->setMethod(RequestMethod::POST);

        // @remark: One api documentation part says redirect_uri is required another says it's not required
        //          Personally I don't see why a redirect is required or even helpful. Will try without it!
        $request->setData('grant_type', 'authorization_code');
        $request->setData('code', $code);
        $request->header->set('Content-Type', 'application/x-www-form-urlencoded');
        $request->header->set('Authorization', 'Basic ' . \base64_encode($login . ':' . $password));

        $this->expire        = new \DateTime('now');
        $this->refreshExpire = new \DateTime('now');

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
                $this->token        = $response->getData('access_token') ?? '';
                $this->refreshToken = $response->getData('refresh_token') ?? '';

                $this->expire->setTimestamp($this->expire->getTimestamp() + ((int) $response->getData('expires_in')));
                $this->refreshExpire->setTimestamp($this->refreshExpire->getTimestamp() + ((int) $response->getData('refresh_token_expires_in')));

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
    public function refreshToken() : int
    {
        $now = new \DateTime('now');
        if ($this->refreshExpire->getTimestamp() < $now->getTimestamp() - self::TIME_DELTA) {
            return AuthStatus::FAILED;
        }

        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/security/' . self::API_VERSION . '/oauth/refresh';

        $request = new HttpRequest(new HttpUri($uri));

        $request->setMethod(RequestMethod::POST);
        $request->header->set('Content-Type', 'application/x-www-form-urlencoded');
        $request->header->set('Authorization', 'Basic ' . \base64_encode($this->login . ':' . $this->password));

        $request->setData('grant_type', 'refresh_token');
        $request->setData('refresh_token', $this->refreshToken);

        $this->expire        = clone $now;
        $this->refreshExpire = clone $now;

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
                $this->token        = $response->getData('access_token') ?? '';
                $this->refreshToken = $response->getData('refresh_token') ?? '';

                $this->expire->setTimestamp($this->expire->getTimestamp() + ((int) $response->getData('expires_in')));
                $this->refreshExpire->setTimestamp($this->refreshExpire->getTimestamp() + ((int) $response->getData('refresh_token_expires_in')));

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
    public function authApiKey(string $key) : int
    {
        return AuthStatus::FAILED;
    }

    /**
     * {@inheritdoc}
     */
    public function timeInTransit(array $shipFrom, array $receiver, array $package, \DateTime $shipDate) : array
    {
        if (!$this->validateOrReconnectAuth()) {
            return [];
        }

        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/api/shipments/' . self::API_VERSION . '/transittimes';

        $request = new HttpRequest(new HttpUri($uri));

        $request->setMethod(RequestMethod::POST);
        $request->header->set('Content-Type', 'application/json');
        $request->header->set('Authorization', 'Bearer ' . $this->token);
        $request->header->set('transId', ((string) \microtime(true)) . '-' . \bin2hex(\random_bytes(6)));
        $request->header->set('transactionSrc', 'jingga');

        $request->setData('originCountryCode', $shipFrom['country_code']);
        $request->setData('originStateProvince', \substr($shipFrom['state'], 0, 50));
        $request->setData('originCityName', \substr($shipFrom['city'], 0, 50));
        $request->setData('originPostalCode', \substr($shipFrom['zip'], 0, 10));

        $request->setData('destinationCountryCode', $receiver['country_code']);
        $request->setData('destinationStateProvince', \substr($receiver['state'], 0, 50));
        $request->setData('destinationCityName', \substr($receiver['city'], 0, 50));
        $request->setData('destinationPostalCode', \substr($receiver['zip'], 0, 10));
        $request->setData('avvFlag', true);

        $request->setData('billType', $package['type']);
        $request->setData('weight', $package['weight']);
        $request->setData('weightUnitOfMeasure', $package['weight_unit']); // LBS or KGS
        $request->setData('shipmentContentsValue', $package['value']);
        $request->setData('shipmentContentsCurrencyCode', $package['currency']); // 3 char ISO code
        $request->setData('numberOfPackages', $package['count']);

        $request->setData('shipDate', $shipDate->format('Y-m-d'));

        $response = Rest::request($request);
        if ($response->header->status !== 200) {
            return [];
        }

        $services = $response->getDataArray('services');
        $transits = [];

        foreach ($services as $service) {
            $transits[] = [
                'serviceLevel' => $service['serviceLevel'],
                'deliveryDate' => new \DateTime($service['deliveryDaye']),
                'deliveryDateFrom' => null,
                'deliveryDateTo' => null,
            ];
        }

        return $transits;
    }

    /**
     * {@inheritdoc}
     */
    public function ship(array $sender, array $shipFrom, array $receiver, array $package, array $data) : array
    {
        if (!$this->validateOrReconnectAuth()) {
            return [];
        }

        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/api/shipments/' . self::API_VERSION . '/ship';

        $request = new HttpRequest(new HttpUri($uri));

        $request->setMethod(RequestMethod::POST);
        $request->header->set('Authorization', 'Bearer ' . $this->token);
        $request->header->set('transId', ((string) \microtime(true)) . '-' . \bin2hex(\random_bytes(6)));
        $request->header->set('transactionSrc', 'jingga');

        // @todo dangerous goods
        // @todo implement printing standard (pdf-zpl/format and size)

        $body = [
            'Request' => [
                'RequestOption' => 'validate',
                'SubVersion'    => '2205',
            ],
            'Shipment' => [
                'Description' => $package['description'],
                'DocumentsOnlyIndicator' => '0',
                'Shipper' => [
                    'Name'                    => \substr($sender['name'], 0, 35),
                    'AttentionName'           => \substr($sender['fao'], 0, 35),
                    'CompanyDisplayableName'  => \substr($sender['name'], 0, 35),
                    'TaxIdentificationNumber' => \substr($sender['taxid'], 0, 15),
                    'Phone'                   => [
                        'Number' => \substr($sender['phone'], 0, 15),
                    ],
                    'ShipperNumber' => $sender['number'],
                    'EMailAddress'  => \substr($sender['email'], 0, 50),
                    'Address' => [
                        'AddressLine'       => \substr($sender['address'], 0, 35),
                        'City'              => \substr($sender['city'], 0, 30),
                        'StateProvinceCode' => \substr($sender['state'], 0, 5),
                        'PostalCode'        => \substr($sender['zip'], 0, 9),
                        'CountryCode'       => $sender['country_code'],
                    ],
                ],
                'ShipTo' => [
                    'Name'                    => \substr($receiver['name'], 0, 35),
                    'AttentionName'           => \substr($receiver['fao'], 0, 35),
                    'CompanyDisplayableName'  => \substr($receiver['name'], 0, 35),
                    'TaxIdentificationNumber' => \substr($receiver['taxid'], 0, 15),
                    'Phone'                   => [
                        'Number' => \substr($receiver['phone'], 0, 15),
                    ],
                    'ShipperNumber' => $receiver['number'],
                    'EMailAddress'  => \substr($receiver['email'], 0, 50),
                    'Address' => [
                        'AddressLine'       => \substr($receiver['address'], 0, 35),
                        'City'              => \substr($receiver['city'], 0, 30),
                        'StateProvinceCode' => \substr($receiver['state'], 0, 5),
                        'PostalCode'        => \substr($receiver['zip'], 0, 9),
                        'CountryCode'       => $receiver['country_code'],
                    ],
                ],
                /* @todo only allowed for US -> US and PR -> PR shipments?
                'ReferenceNumber' => [
                    'BarCodeIndicator' => '1',
                    'Code' => '',
                    'Value' => '',
                ],
                */
                'Service' => [
                    'Code'        => $data['service_code'],
                    'Description' => \substr($data['service_description'], 0, 35),
                ],
                'InvoiceLineTotal' => [
                    'CurrencyCode'  => $package['currency'],
                    'MonetaryValue' => $package['value'],
                ],
                'NumOfPiecesInShipment'     => $package['count'],
                'CostCenter'                => \substr($package['costcenter'], 0, 30),
                'PackageID'                 => \substr($package['id'], 0, 30),
                'PackageIDBarcodeIndicator' => '1',
                'Package'                   => []
            ],
            'LabelSpecification' => [
                'LabelImageFormat' => [
                    'Code'        => $data['label_code'],
                    'Description' => \substr($data['label_description'], 0, 35),
                ],
                'LabelStockSize' => [
                    'Height' => $data['label_height'],
                    'Width'  => $data['label_width'],
                ]
            ],
            'ReceiptSpecification' => [
                'ImageFormat' => [
                    'Code'        => $data['receipt_code'],
                    'Description' => \substr($data['receipt_description'], 0, 35),
                ]
            ],
        ];

        $packages = [];
        foreach ($package['packages'] as $p) {
            $packages[] = [
                'Description' => \substr($p['description'], 0, 35),
                'Packaging' => [
                    'Code'        => $p['package_code'],
                    'Description' => $p['package_description']
                ],
                'Dimensions' => [
                    'UnitOfMeasurement' => [
                        'Code' => $p['package_dim_unit'], // IN or CM or 00 or 01
                        'Description' => \substr($p['package_dim_unit_description'], 0, 35),
                    ],
                    'Length' => $p['length'],
                    'Width'  => $p['width'],
                    'Height' => $p['height'],
                ],
                'DimWeight' => [
                    'UnitOfMeasurement' => [
                        'Code'        => $p['package_dimweight_unit'],
                        'Description' => \substr($p['package_dimweight_unit_description'], 0, 35),
                    ],
                    'Weight' => $p['weight'],
                ],
                'PackageWeight' => [
                    'UnitOfMeasurement' => [
                        'Code'        => $p['package_weight_unit'],
                        'Description' => \substr($p['package_weight_unit_description'], 0, 35),
                    ],
                    'Weight' => $p['weight'],
                ]

            ];
        }

        $body['Shipment']['Package'] = $packages;

        // Only required if shipper != shipFrom (e.g. pickup location != shipper)
        if (!empty($shipFrom)) {
            $body['Shipment']['ShipFrom'] =  [
                'Name'                    => \substr($shipFrom['name'], 0, 35),
                'AttentionName'           => \substr($shipFrom['fao'], 0, 35),
                'CompanyDisplayableName'  => \substr($shipFrom['name'], 0, 35),
                'TaxIdentificationNumber' => \substr($shipFrom['taxid'], 0, 15),
                'Phone'                   => [
                    'Number' => \substr($shipFrom['phone'], 0, 15),
                ],
                'ShipperNumber' => $shipFrom['number'],
                'EMailAddress'  => \substr($shipFrom['email'], 0, 50),
                'Address' => [
                    'AddressLine'       => \substr($shipFrom['address'], 0, 35),
                    'City'              => \substr($shipFrom['city'], 0, 30),
                    'StateProvinceCode' => \substr($shipFrom['state'], 0, 5),
                    'PostalCode'        => \substr($shipFrom['zip'], 0, 9),
                    'CountryCode'       => $shipFrom['country_code'],
                ],
            ];
        }

        $request->setData('ShipmentRequest', $body);

        $response = Rest::request($request);
        if ($response->header->status !== 200) {
            return [];
        }

        $result = $response->getDataArray('ShipmentResponse') ?? [];

        $shipment = [
            'id' => $result['ShipmentResults']['ShipmentIdentificationNumber'] ?? '',
            'costs' => [
                'service'        => $result['ShipmentResults']['ShipmentCharges']['BaseServiceCharge']['MonetaryValue'] ?? null,
                'transportation' => $result['ShipmentResults']['ShipmentCharges']['TransportationCharges']['MonetaryValue'] ?? null,
                'options'        => $result['ShipmentResults']['ShipmentCharges']['ServiceOptionsCharges']['MonetaryValue'] ?? null,
                'subtotal'       => $result['ShipmentResults']['ShipmentCharges']['TotalCharges']['MonetaryValue'] ?? null,
                'taxes'          => $result['ShipmentResults']['ShipmentCharges']['TaxCharges']['MonetaryValue'] ?? null,
                'taxes_type'     => $result['ShipmentResults']['ShipmentCharges']['TaxCharges']['Type'] ?? null,
                'total'          => $result['ShipmentResults']['ShipmentCharges']['TotalChargesWithTaxes']['MonetaryValue'] ?? null,
                'currency'       => $result['ShipmentResults']['ShipmentCharges']['TotalCharges']['CurrencyCode'] ?? null,
            ],
            'packages' => [],
            'label' => [
                'code'  => '',
                'url'   => $result['ShipmentResults']['LabelURL'] ?? '',
                'barcode' => $result['ShipmentResults']['BarCodeImage'] ?? '',
                'local' => $result['ShipmentResults']['LocalLanguageLabelURL'] ?? '',
                'data'    => '',
            ],
            'receipt' => [
                'code'  => '',
                'url'   => $result['ShipmentResults']['ReceiptURL'] ?? '',
                'local' => $result['ShipmentResults']['LocalLanguageReceiptURL'] ?? '',
                'data' => '',
            ]
            // @todo dangerous goods paper image
        ];

        $packages = [];
        foreach ($result['ShipmentResults']['Packages'] as $package) {
            $packages[] = [
                'id' => $package['TrackingNumber'],
                'label' => [
                    'code'    => $package['ShippingLabel']['ImageFormat']['Code'],
                    'url'      => '',
                    'barcode' => $package['PDF417'],
                    'image'   => $package['ShippingLabel']['GraphicImage'],
                    'browser' => $package['HTMLImage'],
                    'data'    => '',
                ],
                'receipt' => [
                    'code'  => $package['ShippingReceipt']['ImageFormat']['Code'],
                    'image' => $package['ShippingReceipt']['ImageFormat']['GraphicImage'],
                ]
            ];
        }

        $shipment['packages'] = $packages;

        return $shipment;
    }

    /**
     * {@inheritdoc}
     */
    public function cancel(string $shipment, array $packages = []) : bool
    {
        if (!$this->validateOrReconnectAuth()) {
            return false;
        }

        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/api/shipments/' . self::API_VERSION . '/void/cancel/' . $shipment;

        $request = new HttpRequest(new HttpUri($uri));

        $request->setMethod(RequestMethod::DELETE);
        $request->header->set('Authorization', 'Bearer ' . $this->token);
        $request->header->set('transId', ((string) \microtime(true)) . '-' . \bin2hex(\random_bytes(6)));
        $request->header->set('transactionSrc', 'jingga');

        $request->setData('trackingnumber', empty($shipment) ? $shipment : \implode(',', $packages));

        $response = Rest::request($request);
        if ($response->header->status !== 200) {
            return false;
        }

        return ($response->getData('VoidShipmentResponse')['Response']['ResponseStatus']['Code'] ?? '0') === '1';
    }

    /**
     * {@inheritdoc}
     */
    public function track(string $shipment) : array
    {
        if (!$this->validateOrReconnectAuth()) {
            return [];
        }

        $base = self::$ENV === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
        $uri  = $base . '/api/track/v1/details/' . $shipment;

        $request = new HttpRequest(new HttpUri($uri));

        $request->setMethod(RequestMethod::GET);
        $request->header->set('Authorization', 'Bearer ' . $this->token);
        $request->header->set('transId', ((string) \microtime(true)) . '-' . \bin2hex(\random_bytes(6)));
        $request->header->set('transactionSrc', 'jingga');

        $request->setData('locale', 'en_US');
        $request->setData('returnSignature', 'false');

        $response = Rest::request($request);
        if ($response->header->status !== 200) {
            return [];
        }

        $shipments = $response->getDataArray('trackResponse') ?? [];
        $shipments = $shipments['shipment'] ?? [];

        $tracking = [];

        // @todo add general shipment status (not just for individual packages)

        foreach ($shipments as $shipment) {
            $packages = [];
            foreach ($shipment['package'] as $package) {
                $activities = [];
                foreach ($package['activity'] as $activity) {
                    $activities[] = [
                        'date' => new \DateTime($activity['date'] . ' ' . $activity['time']),
                        'description' => '',
                        'location' => [
                            'address' => [
                                $activity['location']['address']['addressLine1'],
                                $activity['location']['address']['addressLine2'],
                                $activity['location']['address']['addressLine3'],
                            ],
                            'city'         => $activity['location']['address']['city'],
                            'country'      => $activity['location']['address']['country'],
                            'country_code' => $activity['location']['address']['country_code'],
                            'zip'          => $activity['location']['address']['postalCode'],
                            'state'        => $activity['location']['address']['stateProvice'],
                        ],
                        'status' => [
                            'code'        => $activity['status']['code'],
                            'statusCode'  => $activity['status']['statusCode'],
                            'description' => $activity['status']['description'],
                        ]
                    ];
                }

                $packages[] = [
                    'status'   => [
                        'code'        => $package['status']['code'],
                        'statusCode'  => $package['status']['statusCode'],
                        'description' => $package['status']['description'],
                    ],
                    'deliveryDate'        => new \DateTime($package['deliveryDate'] . ' ' . $package['deliveryTime']['endTime']),
                    'count'               => $package['packageCount'],
                    'weight'              => $package['weight']['weight'],
                    'weight_unit'         => $package['weight']['unitOfMeasurement'],
                    'activities'          => $activities,
                    'received'            => [
                        'by'        => $package['deliveryInformation']['receivedBy'],
                        'signature' => $package['deliveryInformation']['signature'],
                        'location'  => $package['deliveryInformation']['location'],
                        'date'      => '',
                    ]
                ];
            }

            $tracking[] = $packages;
        }

        return $tracking;
    }

    /**
     * Validates the current authentication and tries to reconnect if the connection timed out
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function validateOrReconnectAuth() : bool
    {
        $status = AuthStatus::OK;
        $now    = new \DateTime('now');

        if ($this->expire->getTimestamp() < $now->getTimestamp() - self::TIME_DELTA) {
            $status = AuthStatus::FAILED;

            if ($this->refreshToken !== '') {
                $status = $this->refreshToken();
            } elseif ($this->login !== '' && $this->password !== '') {
                $status = $this->authLogin($this->login, $this->password, $this->client);
            }
        }

        return $status === AuthStatus::OK
            && $this->expire->getTimestamp() > $now->getTimestamp() - self::TIME_DELTA;
    }
}
