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

use phpOMS\Message\Http\RequestMethod;
use phpOMS\System\MimeType;

/**
 * Provider class.
 *
 * @package phpOMS\Auth\OAuth2\OptionProvider
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class PostAuthOptionProvider implements OptionProviderInterface
{
    public function getAccessTokenOptions(string $method, array $params) : array
    {
        $options = [
            'headers' => ['content-type' => MimeType::M_POST],
        ];

        if ($method === RequestMethod::POST) {
            $options['body'] = $this->getAccessTokenBody($params);
        }

        return $options;
    }

    protected function getAccessTokenBody(array $params) : string
    {
        return \http_build_query($params, null, '&', \PHP_QUERY_RFC3986);
    }
}
