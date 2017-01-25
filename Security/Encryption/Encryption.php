<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Security\Encryption;

/**
 * Encryption/Decryption class.
 *
 * PHP Version 5.6
 *
 * @category   Framework
 * @package    phpOMS\Security\Encryption
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Encryption
{

    /**
     * Encryption key.
     *
     * @var \Memcache
     * @since 1.0.0
     */
    private $key = null;

    /**
     * Algorithm for encryption.
     *
     * @var string
     * @since 1.0.0
     */
    private $cipher = null;

    /**
     * Block size.
     *
     * @var int
     * @since 1.0.0
     */
    private $block = 16;

    /**
     * Encryption mode.
     *
     * @var string
     * @since 1.0.0
     */
    private $mode = MCRYPT_MODE_CBC;

    /**
     * Constructor.
     *
     * @param string $key    Encryption key
     * @param string $cipher Encryption algorithm
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $key, string $cipher = MCRYPT_RIJNDAEL_128)
    {
        $this->key    = $key;
        $this->cipher = $cipher;
    }

    /**
     * Get encryption key.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getKey() : string
    {
        return $this->key;
    }

    /**
     * Set encryption key.
     *
     * @param string $key Encryption key
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setKey(string $key) /* : void */
    {
        $this->key = $key;
    }

    /**
     * Get encryption cipher.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCipher() : string
    {
        return $this->key;
    }

    /**
     * Set encryption cipher.
     *
     * @param string $key Encryption key
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setCipher(string $key) /* : void */
    {
        $this->key = $key;
    }

    /**
     * Get block size.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getBlock() : int
    {
        return $this->block;
    }

    /**
     * Set block size.
     *
     * @param int $block
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setBlock(int $block) /* : void */
    {
        $this->block = $block;
    }

    /**
     * Get encryption mode.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getMode() : string
    {
        return $this->mode;
    }

    /**
     * Set encryption mode.
     *
     * @param string $mode
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setMode(string $mode) /* : void */
    {
        $this->mode = $mode;
    }

    /**
     * Encrypt value.
     *
     * @param string $value Value to encrypt
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function encrpyt(string $value) : string
    {
        $iv    = mcrypt_create_iv($this->getIvSize(), $this->getRandomizer());
        $value = base64_encode($this->padAndMcrypt($value, $iv));
        $mac   = $this->hash($value, $iv = base64_encode($iv));

        return base64_encode(json_encode(compact('iv', 'value', 'mac')));
    }

    /**
     * Get input vector size.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function getIvSize() : int
    {
        return mcrypt_get_iv_size($this->cipher, $this->mode);
    }

    /**
     * Get random data source.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function getRandomizer() : int
    {
        if (defined('MCRYPT_DEV_URANDOM')) {
            return MCRYPT_DEV_URANDOM;
        }

        if (defined('MCRYPT_DEV_RANDOM')) {
            return MCRYPT_DEV_RANDOM;
        }

        mt_srand();

        return MCRYPT_RAND;
    }

    /**
     * Mcrypt padding.
     *
     * @param string $value Value to encrypt
     * @param string $iv    Input vector
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function padAndMcrypt(string $value, string $iv) : string
    {
        $value = $this->addPadding(serialize($value));

        return mcrypt_encrypt($this->cipher, $this->key, $value, $this->mode, $iv);
    }

    /**
     * Add padding.
     *
     * @param string $value Value to encrypt
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function addPadding(string $value) : string
    {
        $pad = $this->block - (strlen($value) % $this->block);

        return $value . str_repeat(chr($pad), $pad);
    }

    /**
     * Create hash of value.
     *
     * @param string $value Value to encrypt
     * @param string $iv    Input vector
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function hash(string $value, string $iv) : string
    {
        return hash_hmac('sha256', $iv . $value, $this->key);
    }

    /**
     * Decrypt value.
     *
     * @param string $payload Payload to decrypt
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function decrypt(string $payload) : string
    {
        $payload = $this->getJsonPayload($payload);
        $value   = base64_decode($payload['value']);
        $iv      = base64_decode($payload['iv']);

        if ($payload === false) {
            return false;
        }

        return unserialize($this->stripPadding($this->mcryptDecrypt($value, $iv)));
    }

    /**
     * Get json from payload.
     *
     * @param string $payload Payload to decrypt
     *
     * @return string|false
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function getJsonPayload(string $payload)
    {
        $payload = json_decode(base64_decode($payload), true);

        if (!$payload || $this->invalidPayload($payload)) {
            return false;
        }

        if (!$this->validMac($payload)) {
            return false;
        }

        return $payload;
    }

    /**
     * Check if payload is valid.
     *
     * @param mixed $payload Payload data
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function invalidPayload($payload) : bool
    {
        return !is_array($payload) || !isset($payload['iv']) || !isset($payload['value']) || !isset($payload['mac']);
    }

    /**
     * Is valid mac.
     *
     * @param mixed $payload Payload data
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function validMac($payload) : bool
    {
        return $this->hash($payload['value'], $payload['iv']) == $payload['mac'];
    }

    /**
     * Remove padding.
     *
     * @param string $value Value to decrypt
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function stripPadding(string $value) : string
    {
        $pad = ord($value[($len = strlen($value)) - 1]);

        return $this->paddingIsValid($pad, $value) ? substr($value, 0, $len - $pad) : $value;
    }

    /**
     * Check if padding is valid.
     *
     * @param string $pad   Padding to check
     * @param string $value Value with padding
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function paddingIsValid(string $pad, string $value) : bool
    {
        $beforePad = strlen($value) - $pad;

        return substr($value, $beforePad) == str_repeat(substr($value, -1), $pad);
    }

    /**
     * Decrypt.
     *
     * @param string $value Value to decrypt
     * @param string $iv    Input vector
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function mcryptDecrypt(string $value, string $iv)
    {
        try {
            return mcrypt_decrypt($this->cipher, $this->key, $value, $this->mode, $iv);
        } catch (\Exception $e) {
            throw new \Exception();
        }
    }
}
