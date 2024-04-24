<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Event;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Event\EventManager;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Event\EventManager::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Event\EventManager: Event manager for managing and executing events')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The event manager has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->event->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('New events can be added')]
    public function testAdd() : void
    {
        self::assertTrue($this->event->attach('group', function() : bool { return true; }, false, false));
        self::assertEquals(1, $this->event->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Events can be cleared')]
    public function testClear() : void
    {
        self::assertTrue($this->event->attach('group', function() : bool { return true; }, false, false));
        self::assertEquals(1, $this->event->count());
        $this->event->clear();

        self::assertEquals(0, $this->event->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Multiple callbacks can be added to an event')]
    public function testAddMultiple() : void
    {
        self::assertTrue($this->event->attach('group', function() : bool { return true; }, false, false));
        self::assertTrue($this->event->attach('group', function() : bool { return true; }, false, false));
        self::assertEquals(1, $this->event->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An event gets executed if all conditions and sub conditions are met')]
    public function testDispatchAfterAllConditions() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->trigger('group', 'id1'));
        self::assertTrue($this->event->trigger('group', 'id2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("An event doesn't get executed if not all conditions and sub conditions are met")]
    public function testDispatchAfterSomeConditionsInvalid() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->trigger('group', 'id1'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('None-existing events cannot be executed/triggered')]
    public function testInvalidEventTrigger() : void
    {
        self::assertFalse($this->event->trigger('invalid'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An event can be triggered with group and id regex matches')]
    public function testDispatchSimilarGroupAndId() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertTrue($this->event->triggerSimilar('/[a-z]+/', '/id\\d/'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An event can be triggered with a fixed group definition and id regex matches')]
    public function testDispatchSimilarId() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertTrue($this->event->triggerSimilar('group', '/id\\d/'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An event can be triggered with regex group matches and fixed id definition')]
    public function testDispatchSimilarGroup() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->triggerSimilar('group', 'id1'));
        self::assertTrue($this->event->triggerSimilar('group', 'id2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid regex match will not triggered an event')]
    public function testDispatchSimilarInvalid() : void
    {
        $this->event->attach('group', 'path_to_execute', false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->triggerSimilar('group', '/id\\d0/'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An event can be defined to reset after all conditions and subconditions are met. Then all conditions and sub conditions must be met again before it gets triggered again.')]
    public function testReset() : void
    {
        self::assertTrue($this->event->attach('group', function() : bool { return true; }, false, true));
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->trigger('group', 'id1'));
        self::assertTrue($this->event->trigger('group', 'id2'));
        self::assertFalse($this->event->trigger('group', 'id2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An event can be defined to not reset after all conditions and subconditions are met. Then an event can be triggered any time.')]
    public function testNoReset() : void
    {
        self::assertTrue($this->event->attach('group', function() : bool { return true; }, false, false));
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertFalse($this->event->trigger('group', 'id1'));
        self::assertTrue($this->event->trigger('group', 'id2'));
        self::assertTrue($this->event->trigger('group', 'id2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An event can be manually removed/detached')]
    public function testDetach() : void
    {
        $this->event->attach('group', function() : bool { return true; }, false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        self::assertEquals(1, $this->event->count());
        self::assertTrue($this->event->detach('group'));
        self::assertEquals(0, $this->event->count());
        self::assertFalse($this->event->trigger('group'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('None-existing events cannot be manually removed/detached')]
    public function testInvalidDetach() : void
    {
        $this->event->attach('group', function() : bool { return true; }, false, true);
        $this->event->addGroup('group', 'id1');
        $this->event->addGroup('group', 'id2');

        $this->event->detach('group');
        self::assertFalse($this->event->detach('group'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An event can be defined to automatically remove itself after all conditions and subconditions are met and it is executed')]
    public function testRemove() : void
    {
        self::assertTrue($this->event->attach('group1', function() : bool { return true; }, true, false));
        self::assertTrue($this->event->attach('group2', function() : bool { return true; }, true, false));

        self::assertEquals(2, $this->event->count());
        $this->event->trigger('group1');
        self::assertEquals(1, $this->event->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Events can be imported from a file')]
    public function testImportEvents() : void
    {
        self::assertTrue($this->event->importFromFile(__DIR__ . '/events.php'));

        self::assertEquals(2, $this->event->count());
        self::assertTrue($this->event->trigger('SomeName1', '', [1, 2, 3]));
        self::assertTrue($this->event->trigger('SomeName2', '', 4));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid event files cannot be imported and return a failure')]
    public function testInvalidImportEvents() : void
    {
        self::assertFalse($this->event->importFromFile(__DIR__ . '/invalid.php'));
    }
}
