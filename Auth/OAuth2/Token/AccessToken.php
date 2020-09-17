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
 */
declare(strict_types=1);

namespace phpOMS\Auth\OAuth2;

/**
 * Access token class.
 *
 * @package phpOMS\Auth\OAuth2
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class AccessToken implements AccessTokenInterface, ResourceOwnerAccessTokenInterface
{
    protected string $accessToken;

    protected ?int $expires = null;

    protected ?string $refreshToken = null;

    protected ?string $resourceOwnerId = null;

    protected array $values = [];

    public function __construct(array $options = [])
    {
        if (!isset($options['access_token'])) {
            throw new \InvalidArgumentException();
        }

        $this->accessToken = $options['access_token'];

        if (isset($options['resource_owner_id'])) {
            $this->resourceOwnerId = $options['resource_owner_id'];
        }

        if (isset($options['refresh_token'])) {
            $this->refreshToken = $options['refresh_token'];
        }

        if (isset($options['expires_in'])) {
            $this->expires = $options['expires_in'] !== 0 ? \time() + $options['expires_in'] : 0;
        } elseif (!empty($options['expires'])) {
            $this->expires = $options['expires'];
        }

        $this->values = \array_diff_key($options, \array_flip([
            'access_token',
            'resource_owner_id',
            'refresh_token',
            'expires_in',
            'expires',
        ]));
    }

    public function getToken() : string
    {
        return $this->accessToken;
    }

    public function getExpires() : ?int
    {
        return $this->expires;
    }

    public function getRefreshToken() : ?string
    {
        return $this->refreshToken;
    }

    public function getResourceOwnerId() : ?string
    {
        return $this->resourceOwnerId;
    }

    public function hasExpired() : bool
    {
        return $this->expires < \time();
    }

    public function __toString()
    {
        return $this->getToken();
    }

    public function jsonSerialize()
    {
        $params = $this->values;

        if (isset($this->accessToken)) {
            $params['access_token'] = $this->accessToken;
        }

        if (isset($this->refreshToken)) {
            $params['refresh_token'] = $this->refreshToken;
        }

        if (isset($this->expires)) {
            $params['expires'] = $this->expires;
        }

        if (isset($this->resourceOwnerId)) {
            $params['resource_owner_id'] = $this->resourceOwnerId;
        }

        return $params;
    }
}
