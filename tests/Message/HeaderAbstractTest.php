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

use phpOMS\Message\HeaderAbstract;

/**
 * @testdox phpOMS\tests\Message\HeaderAbstractTest: Abstract header for requests/responses
 *
 * @internal
 */
class HeaderAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $header = null;

    protected function setUp() : void
    {
        $this->header = new class() extends HeaderAbstract
        {
            public function generate(int $statusCode) : void
            {
            }

            public function getProtocolVersion() : string
            {
                return '1';
            }

            public function set(string $key, string $value, bool $overwrite = false) : bool
            {
                return true;
            }

            public function get(string $key = null) : array
            {
                return [];
            }

            public function has(string $key) : bool
            {
                return true;
            }
        };
    }

    /**
     * @testdox The the status code can be set and returned
     * @covers phpOMS\Message\HeaderAbstract
     * @group framework
     */
    public function testStatusCodeInputOutput() : void
    {
        $this->header->status = 2;
        self::assertEquals(2, $this->header->status);
    }
}
