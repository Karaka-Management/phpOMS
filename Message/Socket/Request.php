<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Message\Socket
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Socket;

use phpOMS\Message\RequestAbstract;

final class Request extends RequestAbstract
{
    public function __construct()
    {
        $this->header = new Header();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigin() : string
    {
        return '127.0.0.1';
    }

    /**
     * Get request language
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getRequestLanguage() : string
    {
        return 'en';
    }

    /**
     * Get request locale
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getLocale() : string
    {
        return 'en_US';
    }

    /**
     * {@inheritdoc}
     */
    public function getBody() : string
    {
        return '';
    }
}
