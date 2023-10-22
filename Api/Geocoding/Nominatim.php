<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\Geocoding
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Geocoding;

use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;

/**
 * Check EU VAT.
 *
 * @package phpOMS\Api\Geocoding
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Nominatim
{
    private static float $lastRun = 0;

    /**
     * {@inheritdoc}
     */
    public static function geocoding(string $country, string $city, string $address = '', string $postal = '') : array
    {
        $URL = 'https://nominatim.openstreetmap.org/search.php?format=jsonv2';

        $request = new HttpRequest(
            new HttpUri(
                $URL . '&country=' . \urlencode($country)
                . '&city=' . \urlencode($city)
                . ($address === '' ? '' : '&street=' . \urlencode($address))
                . ($postal === '' ? '' : '&postalcode=' . \urlencode($postal))
            )
        );
        $request->setMethod(RequestMethod::GET);

        // Required according to the api documentation
        $request->header->set('User-Agent', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:15.0) Gecko/20100101 Firefox/15.0.1');

        // Handling rate limit of the Api
        $time = \microtime(true);
        if ($time - self::$lastRun < 1000000) {
            \usleep((int) (1000000 - ($time - self::$lastRun) + 100));
        }

        $body           = Rest::request($request)->getBody();
        $result['body'] = $body;

        /** @var array $json */
        $json = \json_decode($body, true);
        if ($json === false) {
            return [
                'lat' => 0.0,
                'lon' => 0.0,
            ];
        }

        return [
            'lat' => (float) ($json[0]['lat'] ?? 0.0),
            'lon' => (float) ($json[0]['lon'] ?? 0.0),
        ];
    }
}
