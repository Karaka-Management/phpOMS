<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Message;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Message\ResponseAbstract;

class ResponseAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $response = null;

    protected function setUp() : void
    {
        $this->response = new class extends ResponseAbstract
        {
            public function toArray() : array
            {
                return [1];
            }

            public function getBody() : string
            {
                return '';
            }
        };
    }

    public function testDefault() : void
    {
        self::assertEquals(null, $this->response->get('asdf'));
        self::assertEquals('', $this->response->getBody());
    }

    public function testSetGet() : void
    {
        self::assertEquals([1], $this->response->jsonSerialize());

        $this->response->set('asdf', false);
        self::assertEquals(false, $this->response->get('asdf'));
    }
}
