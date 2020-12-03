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

use phpOMS\Auth\OAuth2\Grant\ClientCredentials;

/**
 * @internal
 */
class ClientCredentialsTest extends \PHPUnit\Framework\TestCase
{
    private ClientCredentials $grant;

    protected function setUp() : void
    {
        $this->grant = new ClientCredentials();
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Grant\ClientCredentials
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals('client_credentials', $this->grant->__toString());
        self::assertEquals(
            [
                'code'   => 'value',
                'option' => '2',
                'test'   => 'value2',
            ],
            $this->grant->prepareRequestParamters(
                [
                    'code' => 'value',
                ],
                [
                    'option' => '2',
                    'test'   => 'value2',
                ]
            )
        );
    }
}
