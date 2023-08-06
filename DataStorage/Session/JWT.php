<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
     * @param string                                                 $secret  Secret (at least 256 bit)
     * @var array{alg:string, typ:string}                            $header  Header
     * @var array{sub:string, ?uid:string, ?name:string, iat:string} $payload Payload
     *
     * @return string hmac(Header64 . Payload64, secret)
     *
     * @since 1.0.0
     */
    private static function createSignature(string $secret, array $header, array $payload) : string
    {
        $header64  = Base64Url::encode(\json_encode($header));
        $payload64 = Base64Url::encode(\json_encode($payload));

        $algorithm = '';
        switch (\strtolower($header['alg'])) {
            default:
                $algorithm = 'sha256';
        }

        return \hash_hmac($algorithm, $header64 . '.' . $payload64, $secret, false);
    }

    /**
     * Create JWT token
     *
     * @param string                                                 $secret  Secret (at least 256 bit)
     * @var array{alg:string, typ:string}                            $header  Header
     * @var array{sub:string, ?uid:string, ?name:string, iat:string} $payload Payload
     *
     * @return string Header64 . Payload64 . hmac(Header64 . Payload64, secret)
     */
    public static function createJWT(string $secret, array $header = [], array $payload = []) : string
    {
        $header64  = Base64Url::encode(\json_encode($header));
        $payload64 = Base64Url::encode(\json_encode($payload));
        $signature = self::createSignature($secret, $header, $payload);

        return $header64 . $payload64 . Base64Url::encode($signature);
    }

    public static function getHeader(string $jwt) : array
    {
        $explode = \explode('.', $jwt);

        if ($explode !== 3) {
            return [];
        }

        try {
            return \json_decode(Base64Url::decode($explode[0]), true);
        } catch (\Throwable $_) {
            return [];
        }
    }

    public static function getPayload(string $jwt) : array
    {
        $explode = \explode('.', $jwt);

        if ($explode !== 3) {
            return [];
        }

        try {
            return \json_decode(Base64Url::decode($explode[1]), true);
        } catch (\Throwable $_) {
            return [];
        }
    }

    /**
     * Validate JWT token integrity
     *
     * @param string $secret  Secret (at least 256 bit)
     * @param string $jwt JWT token [Header64 . Payload64 . hmac(Header64 . Payload64, secret)]
     *
     * @return bool
     */
    public static function validateJWT(string $secret, string $jwt) : bool
    {
        $explode = \explode('.', $jwt);

        if ($explode !== 3) {
            return false;
        }

        try {
            $header    = \json_decode(Base64Url::decode($explode[0]), true);
            $payload   = \json_decode(Base64Url::decode($explode[1]), true);
            $signature = self::createSignature($secret, $header, $payload);

            return \hash_equals($signature, $explode[2]);
        } catch (\Throwable $_) {
            return false;
        }
    }
}
