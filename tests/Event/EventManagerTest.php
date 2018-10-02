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

namespace phpOMS\tests\Event;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Event\EventManager;

class EventManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes()
    {
        $event = new EventManager();

        self::assertObjectHasAttribute('groups', $event);
        self::assertObjectHasAttribute('callbacks', $event);
    }

    public function testDefault()
    {
        $event = new EventManager();

        self::assertEquals(0, $event->count());
        self::assertFalse($event->trigger('invalid'));
    }

    public function testBase()
    {
        $event = new EventManager();

        self::assertTrue($event->attach('group', function() { return true; }, false, false));
        self::assertFalse($event->attach('group', function() { return true; }, false, false));
        self::assertEquals(1, $event->count());
    }

    public function testReset()
    {
        $event = new EventManager();

        self::assertTrue($event->attach('group', function() { return true; }, false, true));
        $event->addGroup('group', 'id1');
        $event->addGroup('group', 'id2');

        self::assertFalse($event->trigger('group', 'id1'));
        self::assertTrue($event->trigger('group', 'id2'));
        self::assertFalse($event->trigger('group', 'id2'));
        self::assertEquals(1, $event->count());
    }

    public function testDetach()
    {
        $event = new EventManager();

        self::assertTrue($event->attach('group', function() { return true; }, false, true));
        $event->addGroup('group', 'id1');
        $event->addGroup('group', 'id2');

        self::assertEquals(1, $event->count());
        self::assertTrue($event->detach('group'));
        self::assertEquals(0, $event->count());
        self::assertFalse($event->trigger('group'));

        self::assertFalse($event->detach('group'));
    }

    public function testRemove()
    {
        $event = new EventManager();

        self::assertTrue($event->attach('group1', function() { return true; }, true, false));
        self::assertTrue($event->attach('group2', function() { return true; }, true, false));
        self::assertEquals(2, $event->count());
        $event->trigger('group1');
        self::assertEquals(1, $event->count());
    }
}
