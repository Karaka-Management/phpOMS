<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Datatypes;

use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Validation\Base\IbanEnum;

/**
 * Iban class.
 *
 * @category   Framework
 * @package    phpOMS\Datatypes
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 *
 * @todo       : there is a bug with Hungary ibans since they have two k (checksums) in their definition
 */
class Iban implements \Serializable
{
    private $iban = '';

    public function __construct(string $iban)
    {
        $this->parse($iban);
    }

    private function parse(string $iban)
    {
        if (!\phpOMS\Validation\Base\Iban::isValid($iban)) {
            throw new \InvalidArgumentException('Invalid IBAN');
        }

        $this->iban = self::normalize($iban);
    }

    public function getCountry() : string
    {
        $code = substr($this->iban, 0, 2);

        if (!ISO3166TwoEnum::isValidValue($code)) {
            throw new \Exception('Invalid country code');
        }

        return $code;
    }

    public function getLength() : int
    {
        return strlen($this->iban);
    }

    private function getSequence(string $sequence) : string
    {
        $country = $this->getCountry();
        $layout  = str_replace(' ', '', IbanEnum::getByName('C_' . $country));

        $start = stripos($layout, $sequence);
        $end   = strrpos($layout, $sequence);

        if ($start === false) {
            return '';
        }

        return substr($this->iban, $start, $end - $start);
    }

    public function getChecksum() : string
    {
        return $this->getSequence('k');
    }

    public function getNationalChecksum() : string
    {
        return $this->getSequence('x');
    }

    public function getBranchCode() : string
    {
        return $this->getSequence('s');
    }

    public function getAccountType() : string
    {
        return $this->getSequence('t');
    }

    public function getCurrency() : string
    {
        return $this->getSequence('m');
    }

    public function getBicBankCode() : string
    {
        return $this->getSequence('a');
    }

    public function getBankCode() : string
    {
        return $this->getSequence('b');
    }

    public function getAccount() : string
    {
        return $this->getSequence('n');
    }

    public function getHoldersKennital() : string
    {
        return $this->getSequence('i');
    }

    public function getOwnerAccountNumber() : string
    {
        return $this->getSequence('n');
    }

    public function getBicCode() : string
    {
        return $this->getSequence('a');
    }

    public function prettyPrint() : string
    {
        return wordwrap($this->iban, 4, ' ', true);
    }

    public static function normalize(string $iban) : string
    {
        return strtoupper(str_replace(' ', '', $iban));
    }

    /**
     * String representation of object
     * @link  http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return $this->prettyPrint();
    }

    /**
     * Constructs the object
     * @link  http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $this->parse($serialized);
    }
}