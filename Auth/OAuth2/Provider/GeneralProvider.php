<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Auth\OAuth2
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 * @see       https://tools.ietf.org/html/rfc6749
 */
declare(strict_types=1);

namespace phpOMS\Auth\OAuth2\Provider;

use phpOMS\Auth\OAuth2\AccessToken;

/**
 * Provider class.
 *
 * @package phpOMS\Auth\OAuth2
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class GeneralProvider
{
    /**
     * Authorization url
     *
     * @var string
     * @since 1.0.0
     */
    private string $urlAuthorize;

    /**
     * Access token url
     *
     * @var string
     * @since 1.0.0
     */
    private string $urlAccessToken;

    public function __construct(array $options = [], array $collaborators = [])
    {
    }

    public function getDefaultScopes() : array
    {
        return $this->scopes;
    }

    private function getAccessTokenMethod() : string
    {
        return $this->accessTokenMethod ?: parent::getAccessTokenMethod();
    }

    private function getAccessTokenResourceOwnerId() : string
    {
        return $this->accessTokenResourceOwnerId ?: parent::getAccessTokenResourceOwnerId();
    }

    private function getScopeSeparator() : string
    {
        return $this->scopeSeparator ?: parent::getScopeSeparator();
    }

    private function createResourceOwner(array $reesponse, AccessToken $token) : GeneralResourceOwner
    {
        return new GeneralResourceOwner($response, $this->responseResourceOwnerId);
    }
}
