<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Auth
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Session;

use phpOMS\Utils\Encoding\Base64Url;

/**
 * JWT class.
 *
 * Creates, parses and validates JWT tokens.
 *
 * Header:    base64url([algo: ..., typ: jwt])
 * Payload:   base64url([...])
 * Signature: hmac(Header . Payload, secret)
 *
 * @package phpOMS\Auth
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class JWT
{
    /**
     * Create JWT signature part
     *
     * @param string                                                   $secret  Secret (at least 256 bit)
     * @param array{alg:string, typ:string}                            $header  Header
     * @param array{sub:string, uid?:string, name?:string, iat:string} $payload Payload
     *
     * @return string hmac(Header64 . Payload64, secret)
     *
     * @since 1.0.0
     */
    private static function createSignature(string $secret, array $header, array $payload) : string
    {
        $headerJson  = \json_encode($header);
        $payloadJson = \json_encode($payload);

        if (!\is_string($headerJson) || !\is_string($payloadJson)) {
            return '';
        }

        $header64  = Base64Url::encode($headerJson);
        $payload64 = Base64Url::encode($payloadJson);

        $algorithm = '';
        $algorithm = 'sha256';

        return \hash_hmac($algorithm, $header64 . '.' . $payload64, $secret, false);
    }

    /**
     * Create JWT token
     *
     * @param string                                                   $secret  Secret (at least 256 bit)
     * @param array{alg:string, typ:string}                            $header  Header
     * @param array{sub:string, uid?:string, name?:string, iat:string} $payload Payload
     *
     * @return string Header64 . Payload64 . hmac(Header64 . Payload64, secret)
     *
     * @since 1.0.0
     */
    public static function createJWT(string $secret, array $header, array $payload) : string
    {
        $headerJson  = \json_encode($header);
        $payloadJson = \json_encode($payload);

        if (!\is_string($headerJson) || !\is_string($payloadJson)) {
            return '';
        }

        $header64  = Base64Url::encode($headerJson);
        $payload64 = Base64Url::encode($payloadJson);

        $signature = self::createSignature($secret, $header, $payload);

        return $header64 . $payload64 . Base64Url::encode($signature);
    }

    /**
     * Get the header from the jwt string
     *
     * @param string $jwt JWT string
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getHeader(string $jwt) : array
    {
        $explode = \explode('.', $jwt);

        if (\count($explode) !== 3) {
            return [];
        }

        $json = \json_decode(Base64Url::decode($explode[0]), true);

        return \is_array($json) ? $json : [];
    }

    /**
     * Get the payload from the jwt string
     *
     * @param string $jwt JWT string
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getPayload(string $jwt) : array
    {
        $explode = \explode('.', $jwt);

        if (\count($explode) !== 3) {
            return [];
        }

        $json = \json_decode(Base64Url::decode($explode[1]), true);

        return \is_array($json) ? $json : [];
    }

    /**
     * Validate JWT token integrity
     *
     * @param string $secret Secret (at least 256 bit)
     * @param string $jwt    JWT token [Header64 . Payload64 . hmac(Header64 . Payload64, secret)]
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function validateJWT(string $secret, string $jwt) : bool
    {
        $explode = \explode('.', $jwt);

        if (\count($explode) !== 3) {
            return false;
        }

        try {
            $header  = \json_decode(Base64Url::decode($explode[0]), true);
            $payload = \json_decode(Base64Url::decode($explode[1]), true);

            if (!\is_array($header) || !\is_array($payload)) {
                return false;
            }

            /** @var array{alg:string, typ:string} $header */
            /** @var array{sub:string, uid?:string, name?:string, iat:string} $payload */
            $signature = self::createSignature($secret, $header, $payload);

            return \hash_equals($signature, $explode[2]);
        } catch (\Throwable $_) {
            return false;
        }
    }
}
