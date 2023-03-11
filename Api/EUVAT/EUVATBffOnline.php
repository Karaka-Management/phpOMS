<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\EUVAT
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\EUVAT;

use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;

/**
 * Check EU VAT.
 *
 * @package phpOMS\Api\EUVAT
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class EUVATBffOnline implements EUVATInterface
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function validate(string $ownVAT, string $otherVAT) : int
    {
        $request = new HttpRequest(new HttpUri('https://evatr.bff-online.de/evatrRPC?UstId_1=' . $ownVAT . '&UstId_2=' . $otherVAT));
        $request->setMethod(RequestMethod::GET);

        $matches = [];
        try {
            $body = Rest::request($request)->getBody();


            \preg_match('/ErrorCode.*?(\d+)/s', $body, $matches);

            if ((int) ($matches[1] ?? 1) === 200) {
                return 0;
            }
        } catch (\Throwable $t) {
            return -1;
        }

        return (int) ($matches[1] ?? 1);
    }

    /**
     * {@inheritdoc}
     */
    public static function validateQualified(
        string $ownVAT,
        string $otherVAT,
        string $otherName,
        string $otherCity,
        string $otherPostal,
        string $otherStreet
    ) : array
    {
        $result = [
            'status' => -1,
            'name'   => false,
            'city'   => false,
            'postal' => false,
            'street' => false,
            'response' => '',
        ];

        if (empty($ownVAT)) {
            return $result;
        }

        $request = new HttpRequest(new HttpUri('https://evatr.bff-online.de/evatrRPC?UstId_1=' . $ownVAT . '&UstId_2=' . $otherVAT . '&Firmenname=' . \urlencode($otherName) . '&Ort=' . \urlencode($otherCity) . '&PLZ=' . \urlencode($otherPostal) . '&Strasse=' . \urlencode($otherStreet)));
        $request->setMethod(RequestMethod::GET);

        try {
            $body = Rest::request($request)->getBody();

            $result['response'] = $body;

            $matches = [];
            \preg_match('/ErrorCode.*?(\d+)/s', $body, $matches);
            if ((int) ($matches[1] ?? 1) === 200) {
                $result['status'] = 0;
            }

            $matches = [];
            \preg_match('/Erg_PLZ.*?<string>(A|B|C|D)/s', $body, $matches);
            if (($matches[1] ?? 'B') === 'A' || ($matches[1] ?? 'B') === 'D') {
                $result['postal'] = true;
            }

            $matches = [];
            \preg_match('/Erg_Ort.*?<string>(A|B|C|D)/s', $body, $matches);
            if (($matches[1] ?? 'B') === 'A' || ($matches[1] ?? 'B') === 'D') {
                $result['city'] = true;
            }

            $matches = [];
            \preg_match('/Erg_Str.*?<string>(A|B|C|D)/s', $body, $matches);
            if (($matches[1] ?? 'B') === 'A' || ($matches[1] ?? 'B') === 'D') {
                $result['street'] = true;
            }

            $matches = [];
            \preg_match('/Erg_Name.*?<string>(A|B|C|D)/s', $body, $matches);
            if (($matches[1] ?? 'B') === 'A' || ($matches[1] ?? 'B') === 'D') {
                $result['street'] = true;
            }
        } catch (\Throwable $t) {
            return [];
        }

        return $result;
    }
}
