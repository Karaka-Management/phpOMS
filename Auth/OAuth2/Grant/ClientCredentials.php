<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Auth\OAuth2\Grant
 * @copyright Dennis Eichhorn
 * @copyright MIT - Copyright (c) 2013-2018 Alex Bilbie <hello@alexbilbie.com> - thephpleague/oauth2-client
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 * @see       https://tools.ietf.org/html/rfc6749
 */
declare(strict_types=1);

namespace phpOMS\Auth\OAuth2\Grant;

/**
 * Provider class.
 *
 * @package phpOMS\Auth\OAuth2\Grant
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class ClientCredentials extends GrantAbstract
{
    protected function getName() : string
    {
        return 'client_credentials';
    }

    protected function getRequiredRequestParameters() : array
    {
        return [];
    }
}
