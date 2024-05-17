<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Api\EUVAT
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
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
 * @license OMS License 2.0
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
    public static function validate(string $otherVAT, string $ownVAT = '') : array
    {
        $result = [
            'status'  => -1,
            'vat'     => 'B',
            'name'    => '',
            'city'    => '',
            'postal'  => '',
            'address' => '',
            'body'    => '',
        ];

        if (empty($otherVAT) || empty($ownVAT)) {
            return $result;
        }

        $request = new HttpRequest(
            new HttpUri(
                'https://evatr.bff-online.de/evatrRPC?UstId_1=' . $ownVAT . '&UstId_2=' . $otherVAT
            )
        );
        $request->setMethod(RequestMethod::GET);

        $matches = [];
        try {
            $body           = Rest::request($request)->getBody();
            $result['body'] = $body;

            \preg_match('/ErrorCode.*?(\d+)/s', $body, $matches);

            switch ((int) ($matches[1] ?? 1)) {
                case 200:
                    $result['vat'] = 'A';
                    break;
                default:
                    $result['vat'] = 'B';
            }

            $result['status'] = 0;
        } catch (\Throwable $_) {
            return $result;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public static function validateQualified(
        string $otherVAT,
        string $ownVAT,
        string $otherName,
        string $otherCity,
        string $otherPostal,
        string $otherStreet
    ) : array
    {
        $result = [
            'status'  => -1,
            'vat'     => 'B',
            'name'    => 'C',
            'city'    => 'C',
            'postal'  => 'C',
            'address' => 'C',
            'body'    => '',
        ];

        if (empty($otherVAT) || empty($ownVAT)) {
            return $result;
        }

        $request = new HttpRequest(new HttpUri(
            'https://evatr.bff-online.de/evatrRPC?UstId_1=' . $ownVAT
                . '&UstId_2=' . $otherVAT
                . '&Firmenname=' . \urlencode($otherName)
                . '&Ort=' . \urlencode($otherCity)
                . '&PLZ=' . \urlencode($otherPostal)
                . '&Strasse=' . \urlencode($otherStreet)
            )
        );
        $request->setMethod(RequestMethod::GET);

        try {
            $body           = Rest::request($request)->getBody();
            $result['body'] = $body;

            $matches = [];
            \preg_match('/ErrorCode.*?(\d+)/s', $body, $matches);

            switch ((int) ($matches[1] ?? 1)) {
                case 200:
                    $result['vat'] = 'A';
                    break;
                default:
                    $result['vat'] = 'B';
            }

            $matches = [];
            \preg_match('/Erg_PLZ.*?<string>(A|B|C|D)/s', $body, $matches);
            $result['postal'] = $matches[1] ?? 'B';

            $matches = [];
            \preg_match('/Erg_Ort.*?<string>(A|B|C|D)/s', $body, $matches);
            $result['city'] = $matches[1] ?? 'B';

            $matches = [];
            \preg_match('/Erg_Str.*?<string>(A|B|C|D)/s', $body, $matches);
            $result['address'] = $matches[1] ?? 'B';

            $matches = [];
            \preg_match('/Erg_Name.*?<string>(A|B|C|D)/s', $body, $matches);
            $result['name'] = $matches[1] ?? 'B';

            $result['status'] = 0;
        } catch (\Throwable $_) {
            return $result;
        }

        return $result;
    }
}
