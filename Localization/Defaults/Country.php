<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization\Defaults
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

/**
 * Country class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class Country
{
    /**
     * Country id.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Country name.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $name = '';

    /**
     * Country code.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $code2 = '';

    /**
     * Country code.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $code3 = '';

    /**
     * Country code.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $numeric = 0;

    /**
     * Country subdevision.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $subdevision = '';

    /**
     * Country region.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $region = '';

    /**
     * Country developed.
     *
     * @var bool
     * @since 1.0.0
     */
    protected bool $isDeveloped = false;

    /**
     * Get id
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get country name
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get country code
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCode2() : string
    {
        return $this->code2;
    }

    /**
     * Get country code
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCode3() : string
    {
        return $this->code3;
    }

    /**
     * Get country numeric
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getNumeric() : int
    {
        return $this->numeric;
    }

    /**
     * Get country subdevision
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getSubdevision() : string
    {
        return $this->subdevision;
    }

    /**
     * Get country region
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getRegion() : string
    {
        return $this->region;
    }

    /**
     * Is country developed
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isDeveloped() : bool
    {
        return $this->isDeveloped;
    }
}
