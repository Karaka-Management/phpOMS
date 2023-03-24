<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Localization;

use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Localization\ISO3166TwoEnum;

/**
 * String l11n class.
 *
 * @package phpOMS\Localization
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class BaseStringL11n implements \JsonSerializable
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Ref.
     *
     * @var int
     * @since 1.0.0
     */
    public int $ref = 0;

    /**
     * Language.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $language = ISO639x1Enum::_EN;

    /**
     * Country.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $country = ISO3166TwoEnum::_USA;

    /**
     * Content.
     *
     * @var string
     * @since 1.0.0
     */
    public string $content = '';

    /**
     * Constructor.
     *
     * @param string $content  Localized content
     * @param string $language Language
     * @param string $country  Country
     *
     * @since 1.0.0
     */
    public function __construct(
        string $content = '',
        string $language = ISO639x1Enum::_EN,
        string $country = ISO3166TwoEnum::_USA
    )
    {
        $this->content  = $content;
        $this->language = $language;
        $this->country  = $country;
    }

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
     * Get language
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getLanguage() : string
    {
        return $this->language;
    }

    /**
     * Set language
     *
     * @param string $language Language
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setLanguage(string $language) : void
    {
        $this->language = $language;
    }

    /**
     * Get country
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCountry() : string
    {
        return $this->country;
    }

    /**
     * Set country
     *
     * @param string $country Country
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setCountry(string $country) : void
    {
        $this->country = $country;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'       => $this->id,
            'content'  => $this->content,
            'ref'      => $this->ref,
            'language' => $this->language,
            'country'  => $this->country,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
