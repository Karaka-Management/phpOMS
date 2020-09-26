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

/**
 * Provider class.
 *
 * @package phpOMS\Auth\OAuth2
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class GeneralResourceOwner implements ResourceOwnerInterface
{
    protected array $response;

    protected string $resourceOwnerId;

    public function __construct(array $response, string $resourceOwnerId)
    {
        $this->response = $response;
        $this->resourceOwnerId = $resourceOwnerId;
    }

    public function getId()
    {
        return $this->response[$this->resourceOwnerId];
    }

    public function toArray() : array
    {
        return $this->response;
    }
}
