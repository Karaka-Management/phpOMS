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
class GrantFactory
{
    protected array $registry = [];

    public function setGrant(string $name, GrantAbstract $grant) : self
    {
        $this->registry[$name] = $grant;

        return $this;
    }

    public function getGrant(string $name) : AbstractGrant
    {
        if (!isset($this->registry[$name])) {
            $this->registerDefaultGrant($name);
        }

        return $this->registry[$name];
    }

    protected function registerDefaultGrant(string $name) : self
    {
        $class = \str_replace(' ', '', \ucwords(\str_replace(['-', '_', ' ', $name])));
        $class = 'phpOMS\\OAuth2\\Grant\\' . $class;

        $this->checkGrant($class);

        return $this->setGrant($name, new $class());
    }
}
