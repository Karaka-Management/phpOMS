<?php
/**
 * Karaka
 *
 * PHP Version 8.1
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
final class EUVATVies implements EUVATInterface
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

        if (empty($otherVAT)) {
            return $result;
        }

        $request = new HttpRequest(
            new HttpUri(
                'https://ec.europa.eu/taxation_customs/vies/rest-api/ms/' . \substr($otherVAT, 0, 2) . '/vat/' . \substr($otherVAT, 2) . (
                    $ownVAT !== '' ? '?requesterMemberStateCode=' . \substr($ownVAT, 0, 2) . '&requesterNumber=' . \substr($ownVAT, 2) : ''
                )
            )
        );
        $request->setMethod(RequestMethod::GET);

        try {
            $body           = Rest::request($request)->getBody();
            $result['body'] = $body;

            /** @var array $json */
            $json = \json_decode($body, true);
            if ($json === false) {
                return $result;
            }

            $result = \array_merge($result, self::parseResponse($json));

            $result['status'] = $json['userError'] === 'VALID' ? 0 : -1;
        } catch (\Throwable $t) {
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

        if (empty($otherVAT)) {
            return $result;
        }

        $request = new HttpRequest(
            new HttpUri(
                'https://ec.europa.eu/taxation_customs/vies/rest-api/ms/' . \substr($otherVAT, 0, 2) . '/vat/' . \substr($otherVAT, 2) . (
                    $ownVAT !== ''
                        ? '?requesterMemberStateCode=' . \substr($ownVAT, 0, 2) . '&requesterNumber=' . \substr($ownVAT, 2)
                        : ''
                )
            )
        );
        $request->setMethod(RequestMethod::GET);

        try {
            $body           = Rest::request($request)->getBody();
            $result['body'] = $body;

            /** @var array $json */
            $json = \json_decode($body, true);
            if ($json === false) {
                return $result;
            }

            $result = \array_merge($result, self::parseResponse($json));

            if ($otherName === '') {
                $result['name'] = 'C';
            } elseif ((\stripos($result['name'], $otherName) !== false
                    && \strlen($otherName) / \strlen($result['name']) > 0.8)
                || \levenshtein($otherName, $result['name']) / \strlen($result['name']) < 0.2
            ) {
                $result['name'] = 'A';
            } elseif ($result['name'] === '') {
                $result['name'] = 'C';
            } else {
                $result['name'] = 'B';
            }

            if ($otherCity === '') {
                $result['city'] = 'D';
            } elseif (\stripos($result['city'], $otherCity) !== false) {
                $result['city'] = 'A';
            } elseif ($result['city'] === '') {
                $result['city'] = 'C';
            } else {
                $result['city'] = 'B';
            }

            if ($otherPostal === '') {
                $result['postal'] = 'D';
            } elseif (\stripos($result['postal'], $otherPostal) !== false) {
                $result['postal'] = 'A';
            } elseif ($result['postal'] === '') {
                $result['postal'] = 'C';
            } else {
                $result['postal'] = 'B';
            }

            if ($otherStreet === '') {
                $result['address'] = 'D';
            } elseif (\stripos($result['address'], $otherStreet) !== false
                && \levenshtein($otherStreet, $result['address'], 0) / \strlen($result['address']) < 0.2
            ) {
                $result['address'] = 'A';
            } elseif ($result['address'] === '') {
                $result['address'] = 'C';
            } else {
                $result['address'] = 'B';
            }

            $result['status'] = $json['userError'] === 'VALID' ? 0 : -1;
        } catch (\Throwable $t) {
            return $result;
        }

        return $result;
    }

    /**
     * Parse response.
     *
     * @param array $json JSON response
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function parseResponse(array $json) : array
    {
        $result = [
            'vat'     => '',
            'name'    => '',
            'city'    => '',
            'postal'  => '',
            'address' => '',
        ];

        $result['vat']  = $json['isValid'] ? 'A' : 'B';
        $result['name'] = $json['isValid'];

        $result['city'] = \stripos($json['address'], "\n") !== false
            ? \substr($json['address'], \stripos($json['address'], "\n") + 1)
            : '';

        $result['postal'] = \stripos($json['address'], "\n") !== false
            ? \substr(
                $json['address'],
                \stripos($json['address'], "\n") + 1,
                \stripos($json['address'], ' ', \stripos($json['address'], "\n")) - \stripos($json['address'], "\n") - 1
            )
            : '';

        $result['address'] = \stripos($json['address'], "\n") !== false
            ? \substr($json['address'], 0, \stripos($json['address'], "\n") - 1)
            : $json['address'];

        $result['name']    = $result['name'] === '---' ? '' : $result['name'];
        $result['city']    = $result['city'] === '---' ? '' : $result['city'];
        $result['postal']  = $result['postal'] === '---' ? '' : $result['postal'];
        $result['address'] = $result['address'] === '---' ? '' : $result['address'];

        return $result;
    }
}
