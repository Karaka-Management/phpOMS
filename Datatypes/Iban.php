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
    /**
     * Iban.
     *
     * @var string
     * @since 1.0.0
     */
    private $iban = '';

    /**
     * Constructor.
     *
     * @param string $iban Iban
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $iban)
    {
        $this->parse($iban);
    }

    /**
     * Parsing iban string
     *
     * @param string $iban Iban to parse
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function parse(string $iban)
    {
        $this->iban = self::normalize($iban);

        if (!\phpOMS\Validation\Base\Iban::isValid($this->iban)) {
            throw new \InvalidArgumentException('Invalid IBAN');
        }
    }

    /**
     * Normalize iban
     *
     * @param string $iban Iban to normalize
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function normalize(string $iban) : string
    {
        return strtoupper(str_replace(' ', '', $iban));
    }

    /**
     * Get normalized iban length
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getLength() : int
    {
        return strlen($this->iban);
    }

    /**
     * Get iban checksum
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getChecksum() : string
    {
        return $this->getSequence('k');
    }

    /**
     * Get sequence specified in the layout
     *
     * @param string $sequence Sequence identifier
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function getSequence(string $sequence) : string
    {
        $country = $this->getCountry();
        $layout  = str_replace(' ', '', IbanEnum::getByName('C_' . $country));

        $start = stripos($layout, $sequence);
        $end   = strrpos($layout, $sequence);

        if ($start === false) {
            return '';
        }

        return substr($this->iban, $start, $end - $start + 1);
    }

    /**
     * Get 2 digit country code
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCountry() : string
    {
        return substr($this->iban, 0, 2);
    }

    /**
     * Get national checksum
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getNationalChecksum() : string
    {
        return $this->getSequence('x');
    }

    /**
     * Get branch code
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getBranchCode() : string
    {
        return $this->getSequence('s');
    }

    /**
     * Get account type (cheque account, savings etc.)
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getAccountType() : string
    {
        return $this->getSequence('t');
    }

    /**
     * Get currency
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCurrency() : string
    {
        return $this->getSequence('m');
    }

    /**
     * Get bank code
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getBankCode() : string
    {
        return $this->getSequence('b');
    }

    /**
     * Get account
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getAccount() : string
    {
        return $this->getSequence('c');
    }

    /**
     * Get holder's kennital
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getHoldersKennital() : string
    {
        return $this->getSequence('i');
    }

    /**
     * Get owner account number
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getOwnerAccountNumber() : string
    {
        return $this->getSequence('n');
    }

    /**
     * Get BIC
     *
     * Only very rarely used in iban
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getBicCode() : string
    {
        return $this->getSequence('a');
    }

    /**
     * String representation of object
     * @link  http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize() /* : void */
    {
        return $this->prettyPrint();
    }

    /**
     * Pretty print iban
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function prettyPrint() : string
    {
        return wordwrap($this->iban, 4, ' ', true);
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