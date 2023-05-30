<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Message;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Message\ResponseAbstract;

/**
 * @testdox phpOMS\tests\Message\ResponseAbstractTest: Abstract response
 *
 * @internal
 */
final class ResponseAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $response = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->response = new class() extends ResponseAbstract
        {
            public function toArray() : array
            {
                return [1];
            }

            public function getBody(bool $optimize = false) : string
            {
                return '';
            }
        };
    }

    /**
     * @testdox The response has the expected default values after initialization
     * @covers phpOMS\Message\ResponseAbstract
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertNull($this->response->get('asdf'));
        self::assertEquals('', $this->response->getBody());
        self::assertTrue(ISO639x1Enum::isValidValue($this->response->header->l11n->language));
    }

    /**
     * @testdox The response can be json serialized
     * @covers phpOMS\Message\ResponseAbstract
     * @group framework
     */
    public function testJsonSerialize() : void
    {
        self::assertEquals([1], $this->response->jsonSerialize());
    }

    /**
     * @testdox Data can be set and returned for the response
     * @covers phpOMS\Message\ResponseAbstract
     * @group framework
     */
    public function testDataInputOutput() : void
    {
        $this->response->set('asdf', false);
        self::assertFalse($this->response->get('asdf'));
    }
}
