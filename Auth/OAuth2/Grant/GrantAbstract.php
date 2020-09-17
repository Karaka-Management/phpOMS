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
abstract class GrantAbstract
{
    abstract protected function getName() : string;

    abstract protected function getRequiredRequestParameters() : array;

    public function __toString()
    {
        return $this->getName();
    }

    public function prepareRequestParamters(array $defaults, array $options) : array
    {
        $defaullts['grant_type'] = $this->getName();

        $required = $this->getRequiredRequestParameters();
        $provided = \array_merge($defaults, $options);

        foreach ($required as $name) {
            if (!isset($provided[$name])) {
                throw new \Exception();
            }
        }

        return $provided;
    }
}
