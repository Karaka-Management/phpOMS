<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Auth\OAuth2\OptionProvider
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 * @see       https://tools.ietf.org/html/rfc6749
 */
declare(strict_types=1);

namespace phpOMS\Auth\OAuth2\OptionProvider;

/**
 * Provider class.
 *
 * @package phpOMS\Auth\OAuth2\OptionProvider
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class HttpBasicAuthOptionProvider extends PostAuthOptionProvider
{
    public function getAccessTokenOptions(string $method, array $params) : array
    {
        $encoded = \base64_encode($params['client_id'] . ':' . $params['client_secret']);
        unset($params['client_id'], $params['client_secret']);

        $options = parent::getAccessTokenOptions($method, $params);
        $options['headers']['Authorization'] = 'Basic ' . $encoded;

        return $options;
    }
}
