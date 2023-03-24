<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Git
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Git;

/**
 * Gray encoding class
 *
 * @package phpOMS\Utils\Git
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Tag
{
    /**
     * Name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Message.
     *
     * @var string
     * @since 1.0.0
     */
    private string $message = '';

    /**
     * Constructor
     *
     * @param string $name Tag name/version
     *
     * @since 1.0.0
     */
    public function __construct(string $name = '')
    {
        $this->name = $name;
    }

    /**
     * Get tag message
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * Set tag name
     *
     * @param string $message Tag message
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setMessage(string $message) : void
    {
        $this->message = $message;
    }

    /**
     * Get tag name
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName() : string
    {
        return $this->name;
    }
}
