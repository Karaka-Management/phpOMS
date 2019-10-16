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

namespace phpOMS\tests\Message;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Message\ResponseAbstract;

/**
 * @internal
 */
class ResponseAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $response = null;

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

    public function testDefault() : void
    {
        self::assertNull($this->response->get('asdf'));
        self::assertEquals('', $this->response->getBody());
    }

    public function testSetGet() : void
    {
        self::assertEquals([1], $this->response->jsonSerialize());

        $this->response->set('asdf', false);
        self::assertFalse($this->response->get('asdf'));
    }
}
