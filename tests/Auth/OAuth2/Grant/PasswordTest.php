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

namespace phpOMS\tests\Auth\OAuth2\Grant;

use phpOMS\Auth\OAuth2\Grant\Password;

/**
 * @internal
 */
class PasswordTest extends \PHPUnit\Framework\TestCase
{
    private Password $grant;

    protected function setUp() : void
    {
        $this->grant = new Password();
    }

    public function testDefault() : void
    {
        self::assertEquals('password', $this->grant->__toString());
        self::assertEquals(
            [
                'username' => 'value',
                'password' => '2',
                'test'     => 'value2',
            ],
            $this->grant->prepareRequestParamters(
                [
                    'username' => 'value',
                ],
                [
                    'password' => '2',
                    'test'     => 'value2',
                ]
            )
        );
    }

    public function testMissingDefaultOption() : void
    {
        $this->expectException(\Exception::class);
        $this->grant->prepareRequestParamters(
            [
                'username' => 'value',
            ],
            [
                'option' => '2',
                'test'   => 'value2',
            ]
        );
    }
}
