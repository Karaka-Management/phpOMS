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

use phpOMS\Message\ResponseType;

/**
 * @internal
 */
class ResponseTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(3, ResponseType::getConstants());
        self::assertEquals(0, ResponseType::HTTP);
        self::assertEquals(1, ResponseType::SOCKET);
        self::assertEquals(2, ResponseType::CONSOLE);
    }
}
