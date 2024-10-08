<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Event
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Event;

use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Dispatcher\DispatcherInterface;

/**
 * EventManager class.
 *
 * The event manager allows to define events which can be triggered/executed in an application.
 * This implementation allows to create sub-conditions which need to be met (triggered in advance) before the actual
 * callback is getting executed.
 *
 * What happens after triggering an event (removing the callback, resetting the sub-conditions etc.) depends on the setup.
 *
 * @package phpOMS\Event
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class EventManager implements \Countable
{
    /**
     * Events.
     *
     * @var array<string, array<string, bool>>
     * @since 1.0.0
     */
    private array $groups = [];

    /**
     * Callbacks.
     *
     * @var array<string, array{remove:bool, reset:bool, callbacks:array}>
     * @since 1.0.0
     */
    private array $callbacks = [];

    /**
     * Dispatcher.
     *
     * @var DispatcherInterface
     * @since 1.0.0
     */
    private DispatcherInterface $dispatcher;

    /**
     * Constructor.
     *
     * @param Dispatcher $dispatcher Dispatcher. If no dispatcher is provided a simple general purpose dispatcher is used.
     *
     * @since 1.0.0
     */
    public function __construct(?Dispatcher $dispatcher = null)
    {
        $this->dispatcher = $dispatcher ?? new class() implements DispatcherInterface {
            /**
             * {@inheritdoc}
             */
            public function dispatch(array | string | callable $func, mixed ...$data) : array
            {
                if (!\is_callable($func)) {
                    return [];
                }

                $func(...$data);

                return [];
            }
        };
    }

    /**
     * Add events from file.
     *
     * Files need to return a php array of the following structure:
     * return [
     *      '{EVENT_ID}' => [
     *          'callback' => [
     *              '{DESTINATION_NAMESPACE:method}', // can also be static by using :: between namespace and function name
     *              // more callbacks here
     *          ],
     *      ],
     * ];
     *
     * @param string $path Hook file path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function importFromFile(string $path) : bool
    {
        if (!\is_file($path)) {
            return false;
        }

        /** @noinspection PhpIncludeInspection */
        $hooks = include $path;

        foreach ($hooks as $group => $hook) {
            foreach ($hook['callback'] as $callback) {
                $this->attach($group, $callback, $hook['remove'] ?? false, $hook['reset'] ?? true);
            }
        }

        return true;
    }

    /**
     * Clear all events
     *
     * @return void
     * @since 1.0.0
     */
    public function clear() : void
    {
        $this->groups    = [];
        $this->callbacks = [];
    }

    /**
     * Attach new event
     *
     * @param string          $group    Name of the event (unique)
     * @param string|Callable $callback Callback or route for the event
     * @param bool            $remove   Remove event after triggering it?
     * @param bool            $reset    Reset event after triggering it? Remove must be false!
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function attach(string $group, string | callable $callback, bool $remove = false, bool $reset = false) : bool
    {
        if (!isset($this->callbacks[$group])) {
            $this->callbacks[$group] = ['remove' => $remove, 'reset' => $reset, 'callbacks' => []];
        }

        $this->callbacks[$group]['callbacks'][] = $callback;
        $this->addGroup($group, '');

        return true;
    }

    /**
     * Trigger event based on regex for group and/or id.
     *
     * This trigger function allows the group to be a regex in either this function call or in the definition of the group.
     *
     * @param string $group Name of the event (can be regex)
     * @param string $id    Sub-requirement for event (can be regex)
     * @param mixed  $data  Data to pass to the callback
     *
     * @return bool returns true on successfully triggering ANY event, false if NO event could be triggered which also includes sub-requirements missing
     *
     * @since 1.0.0
     */
    public function triggerSimilar(string $group, string $id = '', mixed $data = null) : bool
    {
        if (empty($this->callbacks)) {
            return false;
        }

        $groupIsRegex = \str_starts_with($group, '/');
        $idIsRegex    = \str_starts_with($id, '/');

        $groups = [];
        foreach ($this->groups as $groupName => $_) {
            $groupNameIsRegex = \str_starts_with($groupName, '/');

            if ($groupIsRegex) {
                if (\preg_match($group, $groupName) === 1) {
                    $groups[$groupName] = [];
                }
            } elseif ($groupNameIsRegex && \preg_match($groupName, $group) === 1) {
                $groups[$groupName] = [];
            } elseif ($groupName === $group) {
                $groups[$groupName] = [];
            }
        }

        foreach ($groups as $groupName => $_) {
            foreach ($this->groups[$groupName] as $idName => $_2) {
                $idNameIsRegex = \str_starts_with($idName, '/');

                if ($idIsRegex) {
                    if (\preg_match($id, $idName) === 1) {
                        $groups[$groupName][] = $idName;
                    }
                } elseif ($idNameIsRegex && \preg_match($idName, $id) === 1) {
                    $groups[$groupName][] = $id;
                } elseif ($idName === $id) {
                    $groups[$groupName] = [];
                }
            }

            if (empty($groups[$groupName])) {
                $groups[$groupName][] = $id;
            }
        }

        if (!\is_array($data)) {
            $data = [$data];
        }

        $data['@triggerGroup'] ??= $group;

        $triggerValue = false;
        foreach ($groups as $groupName => $ids) {
            foreach ($ids as $id) {
                $triggerValue = $this->trigger($groupName, $id, $data) || $triggerValue;
            }
        }

        return $triggerValue;
    }

    /**
     * Trigger event
     *
     * @param string $group Name of the event
     * @param string $id    Sub-requirement for event
     * @param mixed  $data  Data to pass to the callback
     *
     * @return bool returns true on successfully triggering the event, false if the event couldn't be triggered which also includes sub-requirements missing
     *
     * @since 1.0.0
     */
    public function trigger(string $group, string $id = '', mixed $data = null) : bool
    {
        if (!isset($this->callbacks[$group])) {
            return false;
        }

        if (isset($this->groups[$group])) {
            $this->groups[$group][$id] = true;
        }

        if ($this->hasOutstanding($group)) {
            return false;
        }

        foreach ($this->callbacks[$group]['callbacks'] as $func) {
            if (\is_array($data)) {
                $data['@triggerGroup'] ??= $group;
                $data['@triggerId'] = $id;
            } else {
                $data = [
                    $data,
                ];

                $data['@triggerGroup'] = $group;
                $data['@triggerId']    = $id;
            }

            $this->dispatcher->dispatch($func, ...\array_values($data));
        }

        if ($this->callbacks[$group]['remove']) {
            $this->detach($group);
        } elseif ($this->callbacks[$group]['reset']) {
            $this->reset($group);
        }

        return true;
    }

    /**
     * Reset group
     *
     * @param string $group Name of the event
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function reset(string $group) : void
    {
        if (!isset($this->groups[$group])) {
            return; // @codeCoverageIgnore
        }

        foreach ($this->groups[$group] as $id => $ok) {
            $this->groups[$group][$id] = false;
        }
    }

    /**
     * Check if a group has missing sub-requirements
     *
     * @param string $group Name of the event
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function hasOutstanding(string $group) : bool
    {
        if (!isset($this->groups[$group])) {
            return false; // @codeCoverageIgnore
        }

        foreach ($this->groups[$group] as $ok) {
            if (!$ok) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detach an event
     *
     * @param string $group Name of the event
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function detach(string $group) : bool
    {
        $result1 = $this->detachCallback($group);
        $result2 = $this->detachGroup($group);

        return $result1 || $result2;
    }

    /**
     * Detach an event
     *
     * @param string $group Name of the event
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function detachCallback(string $group) : bool
    {
        if (isset($this->callbacks[$group])) {
            unset($this->callbacks[$group]);

            return true;
        }

        return false;
    }

    /**
     * Detach an event
     *
     * @param string $group Name of the event
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function detachGroup(string $group) : bool
    {
        if (isset($this->groups[$group])) {
            unset($this->groups[$group]);

            return true;
        }

        return false;
    }

    /**
     * Add sub-requirement for event
     *
     * @param string $group Name of the event
     * @param string $id    ID of the sub-requirement
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addGroup(string $group, string $id) : void
    {
        if (!isset($this->groups[$group])) {
            $this->groups[$group] = [];
        }

        if (isset($this->groups[$group][''])) {
            unset($this->groups[$group]['']);
        }

        $this->groups[$group][$id] = false;
    }

    /**
     * {@inheritdoc}
     */
    public function count() : int
    {
        return \count($this->callbacks);
    }
}
