<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Stdlib\Map
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Map;

use phpOMS\Utils\Permutation;

/**
 * Multimap utils.
 *
 * @package phpOMS\Stdlib\Map
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class MultiMap implements \Countable
{
    /**
     * Stored values.
     *
     * @var array<int|string, mixed>
     * @since 1.0.0
     */
    private array $values = [];

    /**
     * Associated keys for values.
     *
     * @var array<int|string, int|string>
     * @since 1.0.0
     */
    private array $keys = [];

    /**
     * Key type.
     *
     * @var int
     * @since 1.0.0
     */
    private int $keyType = KeyType::SINGLE;

    /**
     * Order type.
     *
     * @var int
     * @since 1.0.0
     */
    private int $orderType = OrderType::LOOSE;

    /**
     * Constructor.
     *
     * @param int $key   Key type (all keys need to match or just one)
     * @param int $order Order of the keys is important (only required for multiple keys)
     *
     * @since 1.0.0
     */
    public function __construct(int $key = KeyType::SINGLE, int $order = OrderType::LOOSE)
    {
        $this->keyType   = $key;
        $this->orderType = $order;
    }

    /**
     * Add data.
     *
     * @param array<int|float|string> $keys      Keys for value
     * @param mixed                   $value     Value to store
     * @param bool                    $overwrite Add value if key exists
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function add(array $keys, mixed $value, bool $overwrite = false) : bool
    {
        $id        = \count($this->values);
        $inserted  = false;
        $keysBuild = $keys;

        if ($this->keyType !== KeyType::SINGLE) {
            $keysBuild = [\implode(':', $keysBuild)];

            // prevent adding elements if keys are just ordered differently
            if ($this->orderType === OrderType::LOOSE) {
                /** @var array<string[]> $keysToTest */
                $keysToTest = Permutation::permut($keys, [], false);

                foreach ($keysToTest as $test) {
                    $key = \implode(':', $test);

                    if (isset($this->keys[$key])) {
                        if (!$overwrite) {
                            return false;
                        }

                        $keysBuild = [$key];
                        break;
                    }
                }
            }
        }

        foreach ($keysBuild as $key) {
            if ($overwrite || !isset($this->keys[$key])) {
                $id               = $this->keys[$key] ?? $id;
                $this->keys[$key] = $id;

                $inserted = true;
            }
        }

        if ($inserted) {
            $this->values[$id] = $value;
        }

        return $inserted;
    }

    /**
     * Garbage collect unreferenced values/keys
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function garbageCollect() : void
    {
        $this->garbageCollectKeys();
        $this->garbageCollectValues();
    }

    /**
     * Garbage collect unreferenced keys
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function garbageCollectKeys() : void
    {
        foreach ($this->keys as $key => $keyValue) {
            if (!isset($this->values[$keyValue])) {
                unset($this->keys[$key]);
            }
        }
    }

    /**
     * Garbage collect unreferenced values
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function garbageCollectValues() : void
    {
        foreach ($this->values as $valueKey => $value) {
            if (!\in_array($valueKey, $this->keys)) {
                unset($this->values[$valueKey]);
            }
        }
    }

    /**
     * Get data.
     *
     * @param int|string|array $key Key used to identify value
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function get(int|string|array $key) : mixed
    {
        if ($this->keyType === KeyType::MULTIPLE || \is_array($key)) {
            return $this->getMultiple($key);
        }

        return $this->getSingle($key);
    }

    /**
     * Get data.
     *
     * @param int|string $key Key used to identify value
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private function getSingle(int|string $key) : mixed
    {
        return isset($this->keys[$key]) ? $this->values[$this->keys[$key]] ?? null : null;
    }

    /**
     * Get data.
     *
     * @param int|string|array $key Key used to identify value
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private function getMultiple(int|string|array $key) : mixed
    {
        if (\is_array($key)) {
            if ($this->orderType === OrderType::LOOSE) {
                /** @var array<string[]> $keys */
                $keys = Permutation::permut($key, [], false);

                foreach ($keys as $key => $value) {
                    $key = \implode(':', $value);

                    if (isset($this->keys[$key])) {
                        return $this->values[$this->keys[$key]];
                    }
                }
            } else {
                $key = \implode(':', $key);
            }
        }

        return !\is_array($key) && isset($this->keys[$key])
            ? $this->values[$this->keys[$key]] ?? null
            : null;
    }

    /**
     * Set existing key with data.
     *
     * @param int|string|array $key   Key used to identify value
     * @param mixed            $value Value to store
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function set(int|string|array $key, mixed $value) : bool
    {
        if ($this->keyType === KeyType::MULTIPLE || \is_array($key)) {
            return $this->setMultiple($key, $value);
        }

        return $this->setSingle($key, $value);
    }

    /**
     * Set existing key with data.
     *
     * @param int|string|array $key   Key used to identify value
     * @param mixed            $value Value to store
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function setMultiple(int|string|array $key, mixed $value) : bool
    {
        $key = \is_array($key) ? $key : [$key];

        if ($this->orderType !== OrderType::STRICT) {
            /** @var array<string[]> $permutation */
            $permutation = Permutation::permut($key, [], false);

            foreach ($permutation as $permut) {
                if ($this->set(\implode(':', $permut), $value)) {
                    return true;
                }
            }

            return false;
        }

        return $this->set(\implode(':', $key), $value);
    }

    /**
     * Set existing key with data.
     *
     * @param int|string $key   Key used to identify value
     * @param mixed      $value Value to store
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function setSingle(int|string $key, mixed $value) : bool
    {
        if (isset($this->keys[$key])) {
            $this->values[$this->keys[$key]] = $value;

            return true;
        }

        return false;
    }

    /**
     * Remove value and all sibling keys based on key.
     *
     * @param int|string|array $key Key used to identify value
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function remove(int|string|array $key) : bool
    {
        if ($this->keyType === KeyType::MULTIPLE || \is_array($key)) {
            return $this->removeMultiple($key);
        }

        return $this->removeSingle($key);
    }

    /**
     * Remove value and all sibling keys based on key.
     *
     * @param int|string|array $key Key used to identify value
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function removeMultiple(int|string|array $key) : bool
    {
        $key = \is_array($key) ? $key : [$key];

        if ($this->orderType !== OrderType::LOOSE) {
            return $this->remove(\implode(':', $key));
        }

        /** @var array $keys */
        $keys  = Permutation::permut($key, [], false);
        $found = false;

        foreach ($keys as $key => $value) {
            $allFound = $this->remove(\implode(':', $value));

            if ($allFound) {
                $found = true;
            }
        }

        return $found;
    }

    /**
     * Remove value and all sibling keys based on key.
     *
     * @param int|string $key Key used to identify value
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function removeSingle(int|string $key) : bool
    {
        if (isset($this->keys[$key])) {
            $id = $this->keys[$key];

            unset($this->values[$id]);

            $this->garbageCollect();

            return true;
        }

        return false;
    }

    /**
     * Remap key to a different value.
     *
     * Both keys need to exist in the multimap.
     *
     * @param int|string $old Old key
     * @param int|string $new New key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function remap(int|string $old, int|string $new) : bool
    {
        if ($this->keyType === KeyType::MULTIPLE) {
            return false;
        }

        if (isset($this->keys[$old], $this->keys[$new])) {
            $this->keys[$old] = $this->keys[$new];

            $this->garbageCollect();

            return true;
        }

        return false;
    }

    /**
     * Remove key.
     *
     * This only removes the value if no other key exists for this value.
     *
     * @param int|string $key Key used to identify value
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function removeKey(int|string $key) : bool
    {
        if ($this->keyType === KeyType::MULTIPLE) {
            return false;
        }

        return $this->removeKeySingle($key);
    }

    /**
     * Remove key.
     *
     * This only removes the value if no other key exists for this value.
     *
     * @param int|string $key Key used to identify value
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function removeKeySingle(int|string $key) : bool
    {
        if (isset($this->keys[$key])) {
            unset($this->keys[$key]);

            $this->garbageCollect();

            return true;
        }

        return false;
    }

    /**
     * Get all sibling keys.
     *
     * @param int|string|array $key Key to find siblings for
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getSiblings(int|string|array $key) : array
    {
        if ($this->keyType === KeyType::MULTIPLE || \is_array($key)) {
            return $this->getSiblingsMultiple($key);
        }

        return $this->getSiblingsSingle($key);
    }

    /**
     * Get all sibling keys.
     *
     * @param int|string|array $key Key to find siblings for
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getSiblingsMultiple(int|string|array $key) : array
    {
        if ($this->orderType === OrderType::LOOSE) {
            $key = \is_array($key) ? $key : [$key];

            return Permutation::permut($key, [], false);
        }

        return [];
    }

    /**
     * Get all sibling keys.
     *
     * @param int|string $key Key to find siblings for
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function getSiblingsSingle(int|string $key) : array
    {
        $siblings = [];

        if (isset($this->keys[$key])) {
            $id = $this->keys[$key];

            foreach ($this->keys as $found => $value) {
                if ($value === $id && $found !== $key) {
                    $siblings[] = $found;
                }
            }
        }

        return $siblings;
    }

    /**
     * Get all keys.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function keys() : array
    {
        return $this->keys;
    }

    /**
     * Get all values.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function values() : array
    {
        return $this->values;
    }

    /**
     * Count values.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function count() : int
    {
        return \count($this->values);
    }
}
