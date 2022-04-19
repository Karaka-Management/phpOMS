<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Barcode
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Barcode;

/**
 * HIBCC class.
 *
 * @package phpOMS\Utils\Barcode
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class HIBCC
{
    /**
     * Identifier code (3-characters)
     *
     * @var string
     * @since 1.0.0
     */
    private string $identifier = '';

    /**
     * Product id.
     *
     * @var string
     * @since 1.0.0
     */
    private string $productId = '';

    /**
     * Meassure of unit (0-9).
     *
     * @var int
     * @since 1.0.0
     */
    private int $measureOfUnit = 0;

    /**
     * Date format for the shelf life.
     *
     * @var string
     * @since 1.0.0
     */
    private string $dateFormat = 'Y-m-d';

    /**
     * Date of the expiration.
     *
     * @var null|\DateTime
     * @since 1.0.0
     */
    private ?\DateTime $expirationDate = null;

    /**
     * Date of the production.
     *
     * @var null|\DateTime
     * @since 1.0.0
     */
    private ?\DateTime $productionDate = null;

    /**
     * Lot number.
     *
     * @var string
     * @since 1.0.0
     */
    private string $lot = '';

    /**
     * Check value.
     *
     * @var int
     * @since 1.0.0
     */
    private int $checkValue = 0;

    /**
     * Set the identifier.
     *
     * @param string $identifier Identifier
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setIdentifier(string $identifier) : void
    {
        $this->identifier = $identifier;
    }

    /**
     * Get the identifier.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getIdentifier() : string
    {
        return $this->identifier;
    }

    /**
     * Set the product id.
     *
     * @param string $id Product id
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setProductId(string $id) : void
    {
        $this->productId = $id;
    }

    /**
     * Get the product id.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getProductId() : string
    {
        return $this->productId;
    }

    /**
     * Set the product id.
     *
     * @param int $measure Measure of the unit
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setMeasureOfUnit(int $measure) : void
    {
        $this->measureOfUnit = $measure;
    }

    /**
     * Get the measure of the unit.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getMeasureOfUnit() : int
    {
        return $this->measureOfUnit;
    }

    /**
     * Set the date format.
     *
     * @param string $format Date format
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDateFormat(string $format) : void
    {
        $this->dateFormat = $format;
    }

    /**
     * Get the date format.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getDateFormat() : string
    {
        return $this->dateFormat;
    }

    /**
     * Set the expiration date.
     *
     * @param \DateTime $date Expiration date
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setExpirationDate(\DateTime $date) : void
    {
        $this->expirationDate = $date;
    }

    /**
     * Get the expiration date.
     *
     * @return null|\DateTime
     *
     * @since 1.0.0
     */
    public function getExpirationDate() : ?\DateTime
    {
        return $this->expirationDate;
    }

    /**
     * Set the production date.
     *
     * @param \DateTime $date Production date
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setPrductionDate(\DateTime $date) : void
    {
        $this->productionDate = $date;
    }

    /**
     * Get the production date.
     *
     * @return null|\DateTime
     *
     * @since 1.0.0
     */
    public function getProductionDate() : ?\DateTime
    {
        return $this->productionDate;
    }

    /**
     * Set the lot.
     *
     * @param string $lot Lot number
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setLot(string $lot) : void
    {
        $this->lot = $lot;
    }

    /**
     * Get the lot number.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getLot() : string
    {
        return $this->lot;
    }

    /**
     * Get the check value.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getCheckValue() : int
    {
        return $this->checkValue;
    }

    /**
     * Get the primary DI.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPrimaryDi() : string
    {
        return '';
    }

    /**
     * Get the secondary DI.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getSecondaryDi() : string
    {
        return '';
    }
}
