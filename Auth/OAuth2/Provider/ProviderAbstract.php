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

use phpOMS\Auth\OAuth2\Grant\GrantAbstract;
use phpOMS\Auth\OAuth2\Grant\GrantFactory;
use phpOMS\Auth\OAuth2\OptionProvider\OptionProviderInterface;
use phpOMS\Auth\OAuth2\OptionProvider\PostAuthOptionProvider;
use phpOMS\Auth\OAuth2\Token\AccessToken;
use phpOMS\Auth\OAuth2\Token\AccessTokenInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Uri\UriFactory;
use phpOMS\Utils\ArrayUtils;

/**
 * Provider class.
 *
 * @package phpOMS\Auth\OAuth2\Provider
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class ProviderAbstract
{
    protected const ACCESS_TOKEN_RESOURCE_OWNER_ID = null;

    protected string $clientId;

    protected string $clientSecret;

    protected string $redirectUri;

    protected string $state;

    protected GrantFactory $grantFactory;

    protected ReuqestFactory $requestFactory;

    protected OptionProviderInterface $optionProvider;

    public function __construct(array $options = [], array $collaborators = [])
    {
        foreach ($options as $key => $option) {
            if (\property_exists($this, $key)) {
                $this->{$key} = $option;
            }
        }

        $this->setGrantFactory($collaborators['grantFactory'] ?? new GrantFactory());
        $this->setRequestFactory($collaborators['requestFactory'] ?? new RequestFactory());
        $this->setOptionProvider($collaborators['optionProvider'] ?? new PostAuthOptionProvider());
    }

    public function setGrantFactory(GrantFactory $factory) : self
    {
        $this->grantFactory = $factory;

        return $this;
    }

    public function getGrantFactory() : GrantFactory
    {
        return $this->grantFactory;
    }

    public function setRequestFactory(RequestFactory $factory) : self
    {
        $this->requestFactory = $factory;

        return $this;
    }

    public function getRequestFactory() : RequestFactory
    {
        return $this->requestFactory;
    }

    public function setOptionProvider(OptionProviderInterface $provider) : self
    {
        $this->optionProvider = $provider;

        return $this;
    }

    public function getOptionProvider() : OptionProviderInterface
    {
        return $this->optionProvider;
    }

    public function getState() : string
    {
        return $this->state;
    }

    abstract public function getBaseAuthorizationUrl() : string;

    abstract public function getBaseAccessTokenUrl(array $params = []) : string;

    abstract public function getResourceOwnerDetailsUrl(AccessToken $token) : string;

    protected function getRandomState(int $length = 32) : string
    {
        return \bin2hex(\random_bytes($length / 2));
    }

    abstract protected function getDefaultScopes() : array;

    protected function getScopeSeparator() : string
    {
        return ',';
    }

    protected function getAuthorizationParameters(array $options) : array
    {
        $options['state'] ??= $this->getRandomState();
        $options['scope'] ??= $this->getDefaultScopes();

        $this->state = $options['state'];

        $options += [
            'response_type'   => 'code',
            'approval_prompt' => 'auto',
        ];

        if (\is_array($options['scope'])) {
            $options['scope'] = \implode($this->getScopeSeparator(), $options['scope']);
        }

        $options['redirect_uri'] ??= $this->redirectUri;
        $options['client_id'] = $this->clientId;

        return $options;
    }

    protected function getAuthorizationQuery(array $params) : string
    {
        return \http_build_query($params, '', '&', \PHP_QUERY_RFC3986);
    }

    public function getauthorizationUrl(array $options = []) : string
    {
        $base   = $this->getBaseAuthorizationUrl();
        $params = $this->getAuthorizationParameters($options);
        $query  = $this->getAuthorizationQuery($params);

        return UriFactory::build($base . '?' . $query);
    }

    public function authorize(array $options = [], callable $redirectHandler = null)
    {
        $url = $this->getAuthorizationUrl($options);
        if ($redirectHandler !== null) {
            return $redirectHandler($url, $this);
        }

        // @codeCoverageIgnoreStart
        \header('Location: ' . $url);
        exit;
        // @codeCoverageIgnoreEnd
    }

    protected function getAccessTokenMethod() : string
    {
        return RequestMethod::POST;
    }

    protected function getAccessTokenResourceOwnerId() : ?string
    {
        return static::ACCESS_TOKEN_RESOURCE_OWNER_ID;
    }

    protected function getAccessTokenUrl(array $params) : string
    {
        $url = $this->getBaseAccessTokenUrl($params);

        if ($this->getAccessTokenMethod() === RequestMethod::GET) {
            $query = \http_build_query($params, '', '&', \PHP_QUERY_RFC3986);

            return UriFactory::build($url . '?' . $query);
        }

        return $url;
    }

    protected function getAccessTokenRequest(array $params) : HttpRequest
    {
        $method  = $this->getAccessTokenMethod();
        $url     = $this->getAccessTokenUrl($params);
        $options = $this->getoptionProvider->getAccessTokenOptions($this->getAccessTokenMethod(), $params);

        return $this->createRequest($method, $url, null, $options);
    }

    // string | Grant
    public function getAccessToken($grant, array $options = []) : AccessTokenInterface
    {
        $grant = \is_string($grant) ? $this->grantFactory->getGrant($grant) : $grant;

        $params = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
        ];

        $params   = $grant->prepareRequestParameters($params, $options);
        $request  = $this->getAccessTokenRequest($params);
        $response = $this->getParsedResponse($request);

        $prepared = $this->prepareAccessTokenResponse($response);
        $token    = $this->createAccessToken($prepared, $grant);

        return $token;
    }

    public function createRequest(string $method, string $url, $token, array $options) : HttpRequest
    {
        $defaults = [
            'headers' => $this->getHeaders($token),
        ];

        $options = \array_merge_recursive($defaults, $options);
        $factory = $this->getRequestFactory();

        return $factory->getRequestWithOptions($method, $url, $options);
    }

    public function getParsedResponse(HttpRequest $request)
    {
        $response = $request->rest();
        $parsed   = $this->parseResponse($response);

        return $parsed;
    }

    protected function parseResponse(HttpResponse $response) : array
    {
        $content = $response->getBody();
        $type    = \implode(';', (array) $response->getHeader()->get('Content-Type'));

        if (\stripos($type, 'urlencoded') !== false) {
            \parse_str($content, $parsed);

            return $parsed;
        }

        try {
            return \json_decode($content, true);
        } catch (\Throwable $t) {
            return [];
        }
    }

 // todo: consider to make bool

    protected function prepareAccessTokenResponse(array $result) : array
    {
        if (($id = $this->getAccesstokenResourceOwnerId()) !== null) {
            $result['resource_owner_id'] = ArrayUtils::getArray($id, $result, '.');
        }

        return $result;
    }

    protected function createAccessToken(array $response, GrantAbstract $grant) : AccessTokenInterface
    {
        return new AccessToken($response);
    }

    abstract protected function createResourceOwner(array $response, AccessToken $token) : ResourceOwnerInterface;

    public function getResourceOwner(AccessToken $token) : ResourceOwnerInterface
    {
        $response = $this->fetchResourceOwnerDetails($token);

        return $this->createResourceOwner($response, $token);
    }

    protected function fetchResourceOwnerDetails(AccessToken $token)
    {
        $url      = $this->getResourceOwnerDetailsUrl($token);
        $request  = $this->createRequest(RequestMethod::GET, $url, $token, []);
        $response = $this->getParsedResponse($request);

        return $response;
    }

    protected function getDefaultHeaders() : array
    {
        return [];
    }

    protected function getAuthorizationHeaders($token = null) : array
    {
        return [];
    }

    public function getHeaders($token = null) : array
    {
        return $token === null
            ? $this->getDefaultHeaders()
            : \array_merge($this->getDefaultHeaders(), $this->getAuthorizationHeaders());
    }
}
