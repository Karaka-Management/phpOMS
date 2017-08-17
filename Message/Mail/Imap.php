<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

/**
 * Imap mail class.
 *
 * @category   Framework
 * @package    phpOMS\Message\Mail
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Imap extends EmailAbstract
{
    public function __construct(string $host = 'localhost', int $port = 25, int $timeout = 30, bool $ssl = false)
    {
        parent::__construct($host, $port, $timeout, $options);
    }

    public function connect(string $user = '', string $pass = '')
    {
        $this->mailbox = '{' . $this->host . ':' . $this->port . '/imap}';
        parent::connect();
    }
}
