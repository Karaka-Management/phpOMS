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

use phpOMS\Message\RequestSource;

/**
 * @internal
 */
class RequestSourceTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(4, RequestSource::getConstants());
        self::assertEquals(0, RequestSource::WEB);
        self::assertEquals(1, RequestSource::CONSOLE);
        self::assertEquals(2, RequestSource::SOCKET);
        self::assertEquals(3, RequestSource::UNDEFINED);
    }
}
