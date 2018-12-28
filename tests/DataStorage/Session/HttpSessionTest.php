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

namespace phpOMS\tests\DataStorage\Session;

use phpOMS\DataStorage\Session\HttpSession;

class HttpSessionTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $session = new HttpSession();
        self::assertEquals(null, $session->get('key'));
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
