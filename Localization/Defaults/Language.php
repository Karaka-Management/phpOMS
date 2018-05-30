<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Localization\Defaults
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

/**
 * Language class.
 *
 * @package    phpOMS\Localization\Defaults
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Language
{
    /**
     * Language id.
     *
     * @var int
     * @since 1.0.0
     */
    private $id = 0;

    /**
     * Language name.
     *
     * @var string
     * @since 1.0.0
     */
    private $name = '';

    /**
     * Language native.
     *
     * @var string
     * @since 1.0.0
     */
    private $native = '';

    /**
     * Language code.
     *
     * @var string
     * @since 1.0.0
     */
    private $code2 = '';

    /**
     * Language code.
     *
     * @var string
     * @since 1.0.0
     */
    private $code3 = '';

    /**
     * Get language name
     *
     * @return string
     *
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getCode3() : string
    {
        return $this->code3;
    }
}
