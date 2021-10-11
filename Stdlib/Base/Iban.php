<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Stdlib\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

use phpOMS\Validation\Finance\IbanEnum;

/**
 * Iban class.
 *
 * @package phpOMS\Stdlib\Base
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Iban implements \Serializable
{
    /**
     * Iban.
     *
     * @var string
     * @since 1.0.0
     */
    private string $iban = '';

    /**
     * Constructor.
     *
     * @param string $iban Iban
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    private function parse(string $iban) : void
    {
        $this->iban = self::normalize($iban);

        if (!\phpOMS\Validation\Finance\Iban::isValid($this->iban)) {
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function getLength() : int
    {
        return \strlen($this->iban);
    }

    /**
     * Get iban checksum
     *
     * @return string
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    private function getSequence(string $sequence) : string
    {
        $country = $this->getCountry();
        $layout  = str_replace(' ', '', IbanEnum::getByName('C_' . $country));
        $start   = stripos($layout, $sequence);
        $end     = strrpos($layout, $sequence);

        if ($start === false || $end === false) {
            return '';
        }

        $sequence = substr($this->iban, $start, $end - $start + 1);

        return $sequence === false ? '' : $sequence;
    }

    /**
     * Get 2 digit country code
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCountry() : string
    {
        $country = substr($this->iban, 0, 2);

        return $country === false ? '?' : $country;
    }

    /**
     * Get national checksum
     *
     * @return string
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function getBicCode() : string
    {
        return $this->getSequence('a');
    }

    /**
     * {@inheritdoc}
     */
    public function serialize() : string
    {
        return $this->prettyPrint();
    }

    /**
     * Pretty print iban
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function prettyPrint() : string
    {
        return wordwrap($this->iban, 4, ' ', true);
    }

    /**
     * Constructs the object
     * @link  http://php.net/manual/en/serializable.unserialize.php
     * @param  string $serialized <p>
     *                            The string representation of the object.
     *                            </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized) : void
    {
        $this->parse($serialized);
    }
}
