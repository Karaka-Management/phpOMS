<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Localization\Defaults
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

/**
 * Language class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Language
{
    /**
     * Language id.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Language name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Language native.
     *
     * @var string
     * @since 1.0.0
     */
    public string $native = '';

    /**
     * Language code.
     *
     * @var string
     * @since 1.0.0
     */
    public string $code2 = '';

    /**
     * Language code.
     *
     * @var string
     * @since 1.0.0
     */
    public string $code3 = '';

    /**
     * Language code.
     *
     * @var string
     * @since 1.0.0
     */
    public string $code3Native = '';

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
     * Get language name
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
     * Get language native
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getNative() : string
    {
        return $this->native;
    }

    /**
     * Get language code
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
     * Get language code
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
     * Get language code
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCode3Native() : string
    {
        return $this->code3Native;
    }
}
