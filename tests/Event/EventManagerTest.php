<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Event;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Event\EventManager;

/**
 * @testdox phpOMS\tests\Event\EventManager: Event manager for managing and executing events
 *
 * @internal
 */
final class EventManagerTest extends \PHPUnit\Framework\TestCase
{
    protected EventManager $event;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->event = new EventManager();
    }

    /**
     * @testdox The event manager has the expected member variables
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testAttributes() : void
    {
        self::assertObjectHasAttribute('groups', $this->event);
        self::assertObjectHasAttribute('callbacks', $this->event);
    }

    /**
     * @testdox The event manager has the expected default values after initialization
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->event->count());
    }

    /**
     * @testdox New events can be added
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testAdd() : void
    {
        self::assertTrue($this->event->attach('group', function() { return true; }, false, false));
        self::assertEquals(1, $this->event->count());
    }

    /**
     * @testdox Events can be cleared
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testClear() : void
    {
        self::assertTrue($this->event->attach('group', function() { return true; }, false, false));
        self::assertEquals(1, $this->event->count());
        $this->event->clear();

        self::assertEquals(0, $this->event->count());
    }

    /**
     * @testdox Multiple callbacks can be added to an event
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testAddMultiple() : void
    {
        self::assertTrue($this->event->attach('group', function() { return true; }, false, false));
        self::assertTrue($this->event->attach('group', function() { return true; }, false, false));
        self::assertEquals(1, $this->event->count());
    }

    /**
     * @testdox An event gets executed if all conditions and sub conditions are met
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testDispatchAfterAllConditions() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->trigger('group', 'id1'));
        self::assertTrue($this->event->trigger('group', 'id2'));
    }

    /**
     * @testdox An event doesn't get executed if not all conditions and sub conditions are met
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testDispatchAfterSomeConditionsInvalid() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->trigger('group', 'id1'));
    }

    /**
     * @testdox None-existing events cannot be executed/triggered
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testInvalidEventTrigger() : void
    {
        self::assertFalse($this->event->trigger('invalid'));
    }

    /**
     * @testdox An event can be triggered with group and id regex matches
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testDispatchSimilarGroupAndId() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertTrue($this->event->triggerSimilar('/[a-z]+/', '/id\\d/'));
    }

    /**
     * @testdox An event can be triggered with a fixed group definition and id regex matches
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testDispatchSimilarId() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertTrue($this->event->triggerSimilar('group', '/id\\d/'));
    }

    /**
     * @testdox An event can be triggered with regex group matches and fixed id definition
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testDispatchSimilarGroup() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->triggerSimilar('group', 'id1'));
        self::assertTrue($this->event->triggerSimilar('group', 'id2'));
    }

    /**
     * @testdox A invalid regex match will not triggered an event
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testDispatchSimilarInvalid() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->triggerSimilar('group', '/id\\d0/'));
    }

    /**
     * @testdox An event can be defined to reset after all conditions and subconditions are met. Then all conditions and sub conditions must be met again before it gets triggered again.
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testReset() : void
    {
        self::assertTrue($this->event->attach('group', function() { return true; }, false, true));
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->trigger('group', 'id1'));
        self::assertTrue($this->event->trigger('group', 'id2'));
        self::assertFalse($this->event->trigger('group', 'id2'));
    }

    /**
     * @testdox An event can be defined to not reset after all conditions and subconditions are met. Then an event can be triggered any time.
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testNoReset() : void
    {
        self::assertTrue($this->event->attach('group', function() { return true; }, false, false));
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->trigger('group', 'id1'));
        self::assertTrue($this->event->trigger('group', 'id2'));
        self::assertTrue($this->event->trigger('group', 'id2'));
    }

    /**
     * @testdox An event can be manually removed/detached
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testDetach() : void
    {
        $this->event->attach('group', function() { return true; }, false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertEquals(1, $this->event->count());
        self::assertTrue($this->event->detach('group'));
        self::assertEquals(0, $this->event->count());
        self::assertFalse($this->event->trigger('group'));
    }

    /**
     * @testdox None-existing events cannot be manually removed/detached
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testInvalidDetach() : void
    {
        $this->event->attach('group', function() { return true; }, false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        $this->event->detach('group');
        self::assertFalse($this->event->detach('group'));
    }

    /**
     * @testdox An event can be defined to automatically remove itself after all conditions and subconditions are met and it is executed
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testRemove() : void
    {
        self::assertTrue($this->event->attach('group1', function() { return true; }, true, false));
        self::assertTrue($this->event->attach('group2', function() { return true; }, true, false));

        self::assertEquals(2, $this->event->count());
        $this->event->trigger('group1');
        self::assertEquals(1, $this->event->count());
    }

    /**
     * @testdox Events can be imported from a file
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testImportEvents() : void
    {
        self::assertTrue($this->event->importFromFile(__DIR__ . '/events.php'));

        self::assertEquals(2, $this->event->count());
        self::assertTrue($this->event->trigger('SomeName1', '', [1, 2, 3]));
        self::assertTrue($this->event->trigger('SomeName2', '', 4));
    }

    /**
     * @testdox Invalid event files cannot be imported and return a failure
     * @covers phpOMS\Event\EventManager
     * @group framework
     */
    public function testInvalidImportEvents() : void
    {
        self::assertFalse($this->event->importFromFile(__DIR__ . '/invalid.php'));
    }
}
