<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Security
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Security;

/**
 * Php encryption wrapper class.
 *
 * @package phpOMS\Security
 * @license OMS License 2.0
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
     * @param string $keyHex  Shared key as hex string used for encryption
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function encryptShared(string $message, string $keyHex) : string
    {
        $secretKey  = \sodium_hex2bin($keyHex);
        $nonce      = \random_bytes(\SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = \sodium_crypto_secretbox($message, $nonce, $secretKey);

        $result = \sodium_bin2base64($nonce . $ciphertext, \SODIUM_BASE64_VARIANT_ORIGINAL);

        \sodium_memzero($nonce);
        \sodium_memzero($secretKey);
        \sodium_memzero($ciphertext);

        /*
        \sodium_memzero($message);
        \sodium_memzero($keyHex);
        */

        return $result;
    }

    /**
     * Encrypt a file with a shared key
     *
     * @param string $in     File to encrypt
     * @param string $out    Encrypted file
     * @param string $keyHex Shared key as hex string used for encryption
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function encryptFile(string $in, string $out, string $keyHex) : bool
    {
        $fpSource  = \fopen($in, 'rb');
        $fpEncoded = \fopen($out . '.tmp', 'wb');

        if ($fpSource === false || $fpEncoded === false) {
            return false;
        }

        $secretKey = \sodium_hex2bin($keyHex);
        $nonce     = \random_bytes(\SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        \fwrite($fpEncoded, $nonce);

        while (!\feof($fpSource)) {
            $buffer     = \fread($fpSource, 4096);
            $ciphertext = \sodium_crypto_secretbox($buffer, $nonce, $secretKey);

            \fwrite($fpEncoded, $ciphertext);
        }

        \fclose($fpSource);
        \fclose($fpEncoded);

        if ($in === $out) {
            \unlink($in);
        }

        \rename($out . '.tmp', $out);

        \sodium_memzero($nonce);
        \sodium_memzero($secretKey);
        \sodium_memzero($ciphertext);

        /*
        \sodium_memzero($message);
        \sodium_memzero($keyHex);
        */

        return true;
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
        if ($encrypted === '' || $keyHex === '') {
            return $encrypted;
        }

        $secretKey = \sodium_hex2bin($keyHex);

        $ciphertext = \sodium_base642bin($encrypted, \SODIUM_BASE64_VARIANT_ORIGINAL);
        $nonce      = \mb_substr($ciphertext, 0, \SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = \mb_substr($ciphertext, \SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $plaintext = \sodium_crypto_secretbox_open($ciphertext, $nonce, $secretKey);

        \sodium_memzero($nonce);
        \sodium_memzero($secretKey);
        \sodium_memzero($ciphertext);

        /*
        \sodium_memzero($keyHex);
        */

        return $plaintext === false ? '' : $plaintext;
    }

    public static function decryptFile(string $in, string $out, string $keyHex) : bool
    {
        $fpSource  = \fopen($in, 'rb');
        $fpDecoded = \fopen($out . '.tmp', 'wb');

        if ($fpSource === false || $fpDecoded === false) {
            return false;
        }

        $secretKey = \sodium_hex2bin($keyHex);
        $nonce     = \fread($fpSource, \SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        while (!\feof($fpSource)) {
            $buffer     = \fread($fpSource, 4096);
            $ciphertext = \mb_substr($buffer, \SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

            $plaintext = \sodium_crypto_secretbox_open($ciphertext, $nonce, $secretKey);

            if ($plaintext === false) {
                return false;
            }

            \fwrite($fpDecoded, $plaintext);
        }

        \fclose($fpSource);
        \fclose($fpDecoded);

        if ($in === $out) {
            \unlink($in);
        }

        \rename($out . '.tmp', $out);

        \sodium_memzero($nonce);
        \sodium_memzero($secretKey);
        \sodium_memzero($ciphertext);

        /*
        \sodium_memzero($keyHex);
        */

        return true;
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
        $nonce      = \random_bytes(\SODIUM_CRYPTO_BOX_NONCEBYTES);
        $ciphertext = \sodium_crypto_box($message, $nonce, $key);

        $result = \sodium_bin2base64($nonce . $ciphertext, \SODIUM_BASE64_VARIANT_ORIGINAL);

        \sodium_memzero($key);
        \sodium_memzero($nonce);
        \sodium_memzero($ciphertext);
        \sodium_memzero($privateKey);
        \sodium_memzero($publicKey);

        /*
        \sodium_memzero($message);
        \sodium_memzero($privateKeyHex);
        \sodium_memzero($publicKeyHex);
        */

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
        if ($encrypted === '' || $privateKeyHex === '' || $publicKeyHex === '') {
            return $encrypted;
        }

        $privateKey = \sodium_hex2bin($privateKeyHex);
        $publicKey  = \sodium_hex2bin($publicKeyHex);

        $message    = \sodium_base642bin($encrypted, \SODIUM_BASE64_VARIANT_ORIGINAL);
        $nonce      = \mb_substr($message, 0, \SODIUM_CRYPTO_BOX_NONCEBYTES, '8bit');
        $ciphertext = \mb_substr($message, \SODIUM_CRYPTO_BOX_NONCEBYTES, null, '8bit');

        $key = \sodium_crypto_box_keypair_from_secretkey_and_publickey($privateKey, $publicKey);

        $plaintext = \sodium_crypto_box_open($ciphertext, $nonce, $key);

        \sodium_memzero($key);
        \sodium_memzero($ciphertext);
        \sodium_memzero($nonce);
        \sodium_memzero($privateKey);
        \sodium_memzero($publicKey);

        /*
        \sodium_memzero($message);
        \sodium_memzero($privateKeyHex);
        \sodium_memzero($publicKeyHex);
        */

        return $plaintext === false ? '' : $plaintext;
    }
}
