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

use phpOMS\Auth\OAuth2\Grant\GrantAbstract;

/**
 * @internal
 */
class GrantAbstractTest extends \PHPUnit\Framework\TestCase
{
    private GrantAbstract $grant;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->grant = new class() extends GrantAbstract {
            protected function getName() : string
            {
                return 'TestGrant';
            }

            protected function getRequiredRequestParameters() : array
            {
                return ['test'];
            }
        };
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Grant\GrantAbstract
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals('TestGrant', $this->grant->__toString());
        self::assertEquals(
            [
                'test'   => 'value2',
                'option' => '2',
            ],
            $this->grant->prepareRequestParamters(
                [
                    'test' => 'value',
                ],
                [
                    'option' => '2',
                    'test'   => 'value2',
                ]
            )
        );
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Grant\GrantAbstract
     * @group framework
     */
    public function testMissingDefaultOption() : void
    {
        $this->expectException(\Exception::class);
        $this->grant->prepareRequestParamters(
            [
                'something' => 'value',
            ],
            [
                'option' => '2',
            ]
        );
    }
}
