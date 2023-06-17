<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Uri
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

/**
 * Message interface.
 *
 * @property string $subject Subject
 *
 * @package phpOMS\Uri
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface MessageInterface
{
    public function preSend(string $mailer) : bool;

    public function addTo(string $address, string $name = '') : bool;

    public function addCC(string $address, string $name = '') : bool;

    public function addBCC(string $address, string $name = '') : bool;

    public function addReplyTo(string $address, string $name = '') : bool;

    public function setFrom(string $address, string $name = '') : bool;
}
