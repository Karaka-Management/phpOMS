<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Auth\OAuth2\Grant;

use phpOMS\Auth\OAuth2\Grant\RefreshToken;

/**
 * @internal
 */
class RefreshTokenTest extends \PHPUnit\Framework\TestCase
{
    private RefreshToken $grant;

    protected function setUp() : void
    {
        $this->grant = new RefreshToken();
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Grant\RefreshToken
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals('refresh_token', $this->grant->__toString());
        self::assertEquals(
            [
                'refresh_token' => 'value',
                'option'        => '2',
                'test'          => 'value2',
            ],
            $this->grant->prepareRequestParamters(
                [
                    'refresh_token' => 'value',
                ],
                [
                    'option' => '2',
                    'test'   => 'value2',
                ]
            )
        );
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Grant\RefreshToken
     * @group framework
     */
    public function testMissingDefaultOption() : void
    {
        $this->expectException(\Exception::class);
        $this->grant->prepareRequestParamters(
            [
                'test' => 'value',
            ],
            [
                'option' => '2',
                'test'   => 'value2',
            ]
        );
    }
}
