<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Auth\OAuth2\Grant
 * @copyright Dennis Eichhorn
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
class RefreshToken extends GrantAbstract
{
    protected function getName() : string
    {
        return 'refresh_token';
    }

    protected function getRequiredRequestParameters() : array
    {
        return ['refresh_token'];
    }
}
