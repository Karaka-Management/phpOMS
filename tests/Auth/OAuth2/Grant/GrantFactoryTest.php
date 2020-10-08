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

use phpOMS\Auth\OAuth2\Grant\AuthorizationCode;
use phpOMS\Auth\OAuth2\Grant\GrantAbstract;
use phpOMS\Auth\OAuth2\Grant\GrantFactory;

/**
 * @internal
 */
class GrantFactoryTest extends \PHPUnit\Framework\TestCase
{
    private GrantFactory $factory;

    protected function setUp() : void
    {
        $this->factory = new GrantFactory();
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Grant\GrantFactory
     * @group framework
     */
    public function testGrantGet() : void
    {
        $grant = $this->factory->getGrant('AuthorizationCode');
        self::assertInstanceOf(AuthorizationCode::class, $grant);
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Grant\GrantFactory
     * @group framework
     */
    public function testGrantInputOutput() : void
    {
        $grant = new class() extends GrantAbstract {
            protected function getName() : string
            {
                return 'TestGrant';
            }

            protected function getRequiredRequestParameters() : array
            {
                return ['test'];
            }
        };
        $this->factory->setGrant('test', $grant);

        self::assertInstanceOf(\get_class($grant), $this->factory->getGrant('test'));
    }

    /**
     * @covers phpOMS\Auth\OAuth2\Grant\GrantFactory
     * @group framework
     */
    public function testInvalidGrantGet() : void
    {
        $this->expectException(\Exception::class);
        $this->factory->getGrant('invalid');
    }
}
