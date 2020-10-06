<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Auth\OAuth2\Provider
 * @copyright Dennis Eichhorn
 * @copyright MIT - Copyright (c) 2013-2018 Alex Bilbie <hello@alexbilbie.com> - thephpleague/oauth2-client
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 * @see       https://tools.ietf.org/html/rfc6749
 */
declare(strict_types=1);

namespace phpOMS\Auth\OAuth2\Provider;

use phpOMS\Auth\OAuth2\Token\AccessToken;

/**
 * Provider class.
 *
 * @package phpOMS\Auth\OAuth2\Provider
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class GeneralProvider extends ProviderAbstract
{
    private string $urlAuthorize;

    private string $urlAccessToken;

    private string $urlResourceOwnerDetails;

    private string $accessTokenMethod;

    private string $accessTokenResourceOwnerId;

    private ?array $scopes = null;

    private string $scopeSeparator;

    private string $responseCode;

    private string $responseResourceOwnerId = 'id';

    public function __construct(array $options = [], array $collaborators = [])
    {
        if (!isset($options['urlAuthorize'], $options['urlAccessToken'], $options['urlResourceOwnerDetails'])) {
            throw new \InvalidArgumentException();
        }

        foreach ($options as $key => $option) {
            if (\property_exists($this, $key)) {
                $this->{$key} = $option;
            }
        }

        parent::__construct([], $collaborators);
    }

    public function getBaseAuthorizationUrl() : string
    {
        return $this->urlAuthorize;
    }

    public function getBaseAccessTokenUrl(array $params = []) : string
    {
        return $this->urlAccessToken;
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token) : string
    {
        return $this->urlResourceOwnerDetails;
    }

    public function getDefaultScopes() : array
    {
        return $this->scopes;
    }

    protected function getAccessTokenMethod() : string
    {
        return $this->accessTokenMethod ?: parent::getAccessTokenMethod();
    }

    protected function getAccessTokenResourceOwnerId() : string
    {
        return $this->accessTokenResourceOwnerId ?: parent::getAccessTokenResourceOwnerId();
    }

    protected function getScopeSeparator() : string
    {
        return $this->scopeSeparator ?: parent::getScopeSeparator();
    }

    protected function createResourceOwner(array $response, AccessToken $token) : GeneralResourceOwner
    {
        return new GeneralResourceOwner($response, $this->responseResourceOwnerId);
    }
}
