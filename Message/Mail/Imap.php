<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

/**
 * Imap mail class.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Imap extends EmailAbstract
{
    /**
     * Connect to server
     *
     * @param string $user Username
     * @param string $pass Password
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function connect(string $user = '', string $pass = '') : bool
    {
        $this->mailbox = '{' . $this->host . ':' . $this->port . '/imap}';

        return parent::connect($user, $pass);
    }
}
