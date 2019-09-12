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

namespace phpOMS\tests\DataStorage\Session;

use phpOMS\DataStorage\Session\HttpSession;

/**
 * @internal
 */
class HttpSessionTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $session = new HttpSession();
        self::assertNull($session->get('key'));
        self::assertFalse($session->isLocked());
    }

    public function testGetSet() : void
    {
        $session = new HttpSession(1, false, 1);
        self::assertTrue($session->set('test', 'value'));
        self::assertEquals('value', $session->get('test'));

        self::assertFalse($session->set('test', 'value2', false));
        self::assertEquals('value', $session->get('test'));

        self::assertTrue($session->set('test', 'value3'));
        self::assertEquals('value3', $session->get('test'));

        self::assertTrue($session->remove('test'));
        self::assertFalse($session->remove('test'));

        $session->setSID('abc');
        self::assertEquals('abc', $session->getSID());

        $session->lock();
        self::assertTrue($session->isLocked());
    }
}
