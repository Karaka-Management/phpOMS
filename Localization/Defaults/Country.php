<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Localization\Defaults
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

/**
 * Country class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Country
{
    /**
     * Country id.
     *
     * @var   int
     * @since 1.0.0
     */
    private $id = 0;

    /**
     * Country name.
     *
     * @var   string
     * @since 1.0.0
     */
    private $name = '';

    /**
     * Country code.
     *
     * @var   string
     * @since 1.0.0
     */
    private $code2 = '';

    /**
     * Country code.
     *
     * @var   string
     * @since 1.0.0
     */
    private $code3 = '';

    /**
     * Country code.
     *
     * @var   int
     * @since 1.0.0
     */
    private $numeric = 0;

    /**
     * Country subdevision.
     *
     * @var   string
     * @since 1.0.0
     */
    private $subdevision = '';

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
}
