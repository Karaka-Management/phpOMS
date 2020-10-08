<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Auth\OAuth2\Token;

use phpOMS\Auth\OAuth2\Token\AccessToken;

/**
 * @internal
 */
class AccessTokenTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Auth\OAuth2\Token\AccessToken
     * @group framework
     */
    public function testDefault() : void
    {
        $token = new AccessToken(['access_token' => 'token']);
        self::assertEquals('token', $token->getToken());
        self::assertEquals('token', $token->__toString());
        self::assertEquals(-1, $token->getExpires());
        self::assertNull($token->getRefreshToken());
        self::assertNull($token->getResourceOwnerId());
        self::assertFalse($token->hasExpired());
        self::assertEquals([], $token->getValues());
        self::assertEquals(['access_token' => 'token'], $token->jsonSerialize());
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Token\AccessToken
     * @group framework
     */
    public function testExpiresInputOutput() : void
    {
        $expires = \time();
        $token   = new AccessToken(['access_token' => 'token', 'expires' => $expires]);
        self::assertEquals($expires, $token->getExpires());
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Token\AccessToken
     * @group framework
     */
    public function testExpiresInInputOutput() : void
    {
        $expires = \time();
        $token   = new AccessToken(['access_token' => 'token', 'expires_in' => 10]);
        self::assertFalse($token->hasExpired());
        self::assertTrue($expires < $token->getExpires() && $token->getExpires() < $expires + 20);
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Token\AccessToken
     * @group framework
     */
    public function testHasExpired() : void
    {
        $token   = new AccessToken(['access_token' => 'token', 'expires_in' => -5]);
        self::assertTrue($token->hasExpired());

        $expires = \time();
        $token   = new AccessToken(['access_token' => 'token', 'expires' => $expires - 5]);
        self::assertTrue($token->hasExpired());
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Token\AccessToken
     * @group framework
     */
    public function testResourceOwnerIdInputOutput() : void
    {
        $token = new AccessToken(['access_token' => 'token', 'resource_owner_id' => 'owner']);
        self::assertEquals('owner', $token->getResourceOwnerId());
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Token\AccessToken
     * @group framework
     */
    public function testRefreshTokenInputOutput() : void
    {
        $token = new AccessToken(['access_token' => 'token', 'refresh_token' => 'refresh']);
        self::assertEquals('refresh', $token->getRefreshToken());
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Token\AccessToken
     * @group framework
     */
    public function testValuesInputOutput() : void
    {
        $token = new AccessToken([
            'access_token'      => 'token',
            'resource_owner_id' => 'owner',
            'expires_in'        => 10,
            'refresh_token'     => 'refresh',
            'more'              => 'values',
        ]);

        self::assertEquals(
            ['more' => 'values'],
            $token->getValues()
        );
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Token\AccessToken
     * @group framework
     */
    public function testJsonSeriaize() : void
    {
        $expires = \time() + 10;

        $token = new AccessToken([
            'access_token'      => 'token',
            'resource_owner_id' => 'owner',
            'expires'           => $expires,
            'refresh_token'     => 'refresh',
            'more'              => 'values',
        ]);

        self::assertEquals(
            [
                'access_token'      => 'token',
                'resource_owner_id' => 'owner',
                'expires'           => $expires,
                'refresh_token'     => 'refresh',
                'more'              => 'values',
            ],
            $token->jsonSerialize()
        );
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Token\AccessToken
     * @group framework
     */
    public function testMissingAccessToken() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $token = new AccessToken([]);
    }
}
