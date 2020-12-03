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

namespace phpOMS\tests\Auth\OAuth2\OptionProvider;

use phpOMS\Auth\OAuth2\OptionProvider\HttpBasicAuthOptionProvider;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\System\MimeType;

/**
 * @internal
 */
class HttpBasicAuthOptionProviderTest extends \PHPUnit\Framework\TestCase
{
    private HttpBasicAuthOptionProvider $provider;

    protected function setUp() : void
    {
        $this->provider = new HttpBasicAuthOptionProvider();
    }

    /**
     * @covers phpOMS\Auth\OAuth2\OptionProvider\HttpBasicAuthOptionProvider
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals(
            [
                'headers' => [
                    'content-type'  => MimeType::M_POST,
                    'Authorization' => 'Basic aWQ6c2VjcmV0',
                ],
                'body' => 'para=test&para2=test2',
            ],
            $this->provider->getAccessTokenOptions(RequestMethod::POST, [
                    'para'          => 'test',
                    'para2'         => 'test2',
                    'client_id'     => 'id',
                    'client_secret' => 'secret',
                ]
            )
        );
    }

    /**
     * @covers phpOMS\Auth\OAuth2\OptionProvider\HttpBasicAuthOptionProvider
     * @group framework
     */
    public function testInvalidParams() : void
    {
        self::assertEquals([],
            $this->provider->getAccessTokenOptions(RequestMethod::POST, [
                    'para'      => 'test',
                    'para2'     => 'test2',
                    'client_id' => 'id',
                ]
            )
        );
    }
}
