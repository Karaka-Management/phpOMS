<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Security
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Security;

/**
 * Php encryption wrapper class.
 *
 * @package phpOMS\Security
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class EncryptionHelper
{
    /**
     * Create a shared key used by multiple entities.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function createSharedKey() : string
    {
        $secretKey = \sodium_crypto_secretbox_keygen();

        return \sodium_bin2hex($secretKey);
    }

    /**
     * Encrypt a message with a shared key
     *
     * @param string $message Message to encrypt
     * @param string $key     Shared key used for encryption
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function encryptShared(string $message, string $keyHex) : string
    {
        $secretKey  = \sodium_hex2bin($keyHex);
        $nonce      = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = \sodium_crypto_secretbox($message, $nonce, $secretKey);

        $result = \sodium_bin2base64($nonce . $ciphertext, SODIUM_BASE64_VARIANT_ORIGINAL);

        \sodium_memzero($message);
        \sodium_memzero($nonce);
        \sodium_memzero($secretKey);
        \sodium_memzero($keyHex);

        return $result;
    }

    /**
     * Decrypt an encrypted message
     *
     * @param string $encrypted Encrypted message
     * @param string $keyHex    Shared key in hex
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function decryptShared(string $encrypted, string $keyHex) : string
    {
        $secretKey = \sodium_hex2bin($keyHex);

        $ciphertext = \sodium_base642bin($encrypted, SODIUM_BASE64_VARIANT_ORIGINAL);
        $nonce      = \mb_substr($ciphertext, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = \mb_substr($ciphertext, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $plaintext = \sodium_crypto_secretbox_open($ciphertext, $nonce, $secretKey);

        \sodium_memzero($nonce);
        \sodium_memzero($secretKey);
        \sodium_memzero($ciphertext);

        return $plaintext === false ? '' : $plaintext;
    }

    /**
     * Create a paired keys.
     *
     * @return array{alicePublic:string, alicePrivate:string, bobPublic:string, bobPrivate:string}
     *
     * @since 1.0.0
     */
    public static function createPairedKey() : array
    {
        $bobKeypair    = \sodium_crypto_box_keypair();
        $bobPublicKey  = \sodium_crypto_box_publickey($bobKeypair);
        $bobPrivateKey = \sodium_crypto_box_secretkey($bobKeypair);

        $aliceKeypair    = \sodium_crypto_box_keypair();
        $alicePublicKey  = \sodium_crypto_box_publickey($aliceKeypair);
        $alicePrivateKey = \sodium_crypto_box_secretkey($aliceKeypair);

        return [
            'alicePublic'  => \sodium_bin2hex($alicePublicKey),
            'alicePrivate' => \sodium_bin2hex($alicePrivateKey),
            'bobPublic'    => \sodium_bin2hex($bobPublicKey),
            'bobPrivate'   => \sodium_bin2hex($bobPrivateKey),
        ];
    }

    /**
     * Encrypt a message with a key pair
     *
     * @param string $message       Message to encrypt
     * @param string $privateKeyHex Private key (alicePrivate)
     * @param string $publicKeyHex  Public key (bobPublic)
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function encryptSecret(string $message, string $privateKeyHex, string $publicKeyHex) : string
    {
        $privateKey = \sodium_hex2bin($privateKeyHex);
        $publicKey  = \sodium_hex2bin($publicKeyHex);

        $key        = \sodium_crypto_box_keypair_from_secretkey_and_publickey($privateKey, $publicKey);
        $nonce      = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);
        $ciphertext = \sodium_crypto_box($message, $nonce, $key);

        $result = \sodium_bin2base64($nonce . $ciphertext, SODIUM_BASE64_VARIANT_ORIGINAL);

        \sodium_memzero($message);
        \sodium_memzero($nonce);
        \sodium_memzero($secretKey);
        \sodium_memzero($secretKeyHex);

        return $result;
    }

    /**
     * Decrypt a message with a key pair
     *
     * @param string $encrypted     Message to encrypt
     * @param string $privateKeyHex Private key (bobPrivate)
     * @param string $publicKeyHex  Public key (alicePublic)
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function decryptSecret(string $encrypted, string $privateKeyHex, string $publicKeyHex) : string
    {
        $privateKey = \sodium_hex2bin($privateKeyHex);
        $publicKey  = \sodium_hex2bin($publicKeyHex);

        $message    = \sodium_base642bin($encrypted, SODIUM_BASE64_VARIANT_ORIGINAL);
        $nonce      = \mb_substr($message, 0, SODIUM_CRYPTO_BOX_NONCEBYTES, '8bit');
        $ciphertext = \mb_substr($message, SODIUM_CRYPTO_BOX_NONCEBYTES, null, '8bit');

        $key = \sodium_crypto_box_keypair_from_secretkey_and_publickey($privateKey, $publicKey);

        $plaintext = \sodium_crypto_box_open($ciphertext, $nonce, $key);

        \sodium_memzero($key);
        \sodium_memzero($ciphertext);
        \sodium_memzero($nonce);
        \sodium_memzero($privateKey);

        return $plaintext === false ? '' : $plaintext;
    }
}
