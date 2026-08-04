<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message;

/**
 * Message interface.
 *
 * @package phpOMS\Message
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface MessageInterface
{
    /**
     * Gets the body of the message.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getBody() : string;
}
