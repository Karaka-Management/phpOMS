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

namespace phpOMS\tests\Stdlib\Map;

use phpOMS\Stdlib\Map\KeyType;
use phpOMS\Stdlib\Map\MultiMap;
use phpOMS\Stdlib\Map\OrderType;

/**
 * @testdox phpOMS\tests\Stdlib\Map\MultiMapTest: Map which associates multiple keys with the same value
 *
 * @internal
 */
class MultiMapTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The map has the expected default values and functionality after initialization
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testDefault() : void
    {
        $map = new MultiMap();

        /* Testing default values */
        self::assertNull($map->get('someKey'));
        self::assertEquals(0, $map->count());

        self::assertEmpty($map->keys());
        self::assertEmpty($map->values());
        self::assertEmpty($map->getSiblings('someKey'));
        self::assertFalse($map->removeKey('someKey'));
        self::assertFalse($map->remap('old', 'new'));
        self::assertFalse($map->remove('someKey'));
    }

    /**
     * @testdox Elements with multiple optional keys can be added
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testBasicAddAny() : void
    {
        $map = new MultiMap();

        $inserted = $map->add(['a', 'b'], 'val1');
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
    }

    /**
     * @testdox Elements with multiple optional keys can be returned if any of the keys matches
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testBasicGetAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val1');
        self::assertEquals('val1', $map->get('a'));
        self::assertEquals('val1', $map->get('b'));
    }

    /**
     * @testdox Elements can be forcefully overwritten
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testOverwriteAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val1');
        $inserted = $map->add(['a', 'b'], 'val2', true);

        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get('a'));
        self::assertEquals('val2', $map->get('b'));
    }

    /**
     * @testdox By default elements are not overwritten
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidOverwriteSubkeyAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a'], 'val3');

        self::assertEquals(1, $map->count());
        self::assertFalse($inserted);
        self::assertEquals('val2', $map->get('a'));
        self::assertEquals('val2', $map->get('b'));
    }

    /**
     * @testdox If a element with partially matching keys is already in the map it will be only added for the new key
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testOverwriteCreateAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3');

        self::assertEquals(2, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get('a'));
        self::assertEquals('val2', $map->get('b'));
        self::assertEquals('val3', $map->get('c'));
    }

    /**
     * @testdox If all keys exist in the map no new element will be created
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidOverwriteAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');
        $inserted = $map->add(['a', 'c'], 'val4');
        self::assertEquals(2, $map->count());
        self::assertFalse($inserted);
        self::assertEquals('val2', $map->get('a'));
        self::assertEquals('val2', $map->get('b'));
        self::assertEquals('val3', $map->get('c'));
    }

    /**
     * @testdox Values can be set/replaced by key
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testSetByKeyAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $set = $map->set('b', 'val4');
        self::assertEquals(2, $map->count());
        self::assertTrue($set);
        self::assertEquals('val4', $map->get('b'));
        self::assertEquals('val4', $map->get('a'));
        self::assertEquals('val3', $map->get('c'));
    }

    /**
     * @testdox Values cannot be set/replaced if the key doesn't exist
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidSetByKeyAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $set = $map->set('d', 'val4');
        self::assertFalse($set);
        self::assertEquals(2, $map->count());
    }

    /**
     * @testdox A key cannot be remapped to a none-existing key
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidRemapNewAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $remap = $map->remap('b', 'd');
        self::assertEquals(2, $map->count());
        self::assertFalse($remap);
    }

    /**
     * @testdox A key can be remapped to the value of an existing key
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testRemapAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val4');
        $map->add(['a', 'c'], 'val3');

        $remap = $map->remap('b', 'c');
        self::assertEquals(2, $map->count());
        self::assertTrue($remap);
        self::assertEquals('val3', $map->get('b'));
        self::assertEquals('val4', $map->get('a'));
        self::assertEquals('val3', $map->get('c'));
    }

    /**
     * @testdox If no more keys are associated with a value after a remap the value is removed from the map
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testRemapUnmapAny() : void
    {
        $map = new MultiMap();

        $map->add(['b'], 'val4');
        $map->add(['a', 'c'], 'val3');

        $remap = $map->remap('b', 'c');
        self::assertEquals(1, $map->count());
        self::assertTrue($remap);
        self::assertEquals('val3', $map->get('b'));
        self::assertEquals('val3', $map->get('a'));
        self::assertEquals('val3', $map->get('c'));
    }

    /**
     * @testdox All keys of the map can be returned
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testMapKeysAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $map->set('d', 'val4');
        $map->set('b', 'val4');

        self::assertCount(3, $map->keys());
        self::assertIsArray($map->keys());
    }

    /**
     * @testdox All values of the map can be returned
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testMapValuesAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $map->set('d', 'val4');
        $map->set('b', 'val4');

        self::assertCount(2, $map->values());
        self::assertIsArray($map->values());
    }

    /**
     * @testdox Sibling keys can be found
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testSiblingsAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');

        $siblings = $map->getSiblings('b');
        self::assertEquals(['a'], $siblings);
    }

    /**
     * @testdox If a key doesn't exist or has no siblings no siblings are returned
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidSiblingsAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');

        $siblings = $map->getSiblings('d');
        self::assertEmpty($siblings);
    }

    /**
     * @testdox A key for a value can be removed
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testRemoveAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $removed = $map->remove('c');
        self::assertTrue($removed);
        self::assertCount(2, $map->keys());
        self::assertCount(1, $map->values());
    }

    /**
     * @testdox If the last key of a value is removed the value is also removed from the map
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testRemoveLastAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $map->remove('c');
        $removed = $map->removeKey('a');
        self::assertTrue($removed);
        self::assertEquals(1, $map->count());
        self::assertCount(1, $map->keys());
        self::assertCount(1, $map->values());
    }

    /**
     * @testdox If a key doesn't exist it cannot be removed
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidRemoveAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $removed = $map->remove('d');
        self::assertFalse($removed);
    }

    /**
     * @testdox Elements with multiple required keys can be added
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testBasicAddExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $inserted = $map->add(['a', 'b'], 'val1');
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
    }

    /**
     * @testdox Elements with multiple required keys can be returned if all match
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testBasicGetExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val1');
        self::assertEquals('val1', $map->get(['a', 'b']));
        self::assertEquals('val1', $map->get(['b', 'a']));
    }

    /**
     * @testdox Elements with multiple required keys cannot be returned if they don't match exactly
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testBasicInvalidGetExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val1');
        self::assertNotEquals('val1', $map->get(['b']));
    }

    /**
     * @testdox Elements with multiple required and ordered keys can be added
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testBasicAddExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $inserted = $map->add(['a', 'b'], 'val1');
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
    }

    /**
     * @testdox Elements with multiple required ordered keys can be if all match in the correct order
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testBasicGetExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val1');
        self::assertEquals('val1', $map->get(['a', 'b']));
    }

    /**
     * @testdox Elements with multiple required keys cannot be returned if they don't match exactly in the correct order
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testBasicInvalidOrderedGetExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val1');
        self::assertNull($map->get(['b', 'a']));
    }

    /**
     * @testdox Elements with multiple required keys can be forcefully overwritten
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testOverwriteExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val1');
        $inserted = $map->add(['a', 'b'], 'val2', true);
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
    }

    /**
     * @testdox Elements with multiple required ordered keys can be forcefully overwritten
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testOverwriteExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val1');
        $inserted = $map->add(['a', 'b'], 'val2', true);
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
    }

    /**
     * @testdox An element cannot be added to for multiple required keys if the keys already exist in a different order
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidAddDifferentOrderExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['b', 'a'], 'val3');
        self::assertEquals(1, $map->count());
        self::assertFalse($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
        self::assertEquals('val2', $map->get(['b', 'a']));
    }

    /**
     * @testdox If a element with partially matching multiple keys is already in the map it will be only added for the new key
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testOverwriteCreateExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val1');
        $inserted = $map->add(['b', 'c'], 'val3');
        self::assertEquals(3, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
        self::assertEquals('val3', $map->get(['b', 'c']));
        self::assertEquals('val1', $map->get(['a', 'c']));
    }

    /**
     * @testdox Adding differently ordered keys for multiple required keys will create a new entry in the map
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testAddDifferentlyOrderedKeys() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['b', 'a'], 'val3');
        self::assertEquals(2, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
        self::assertEquals('val3', $map->get(['b', 'a']));
    }

    /**
     * @testdox If all keys for multiple required keys exist in the map no new element will be created
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidOverwriteExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');
        $inserted = $map->add(['a', 'c'], 'val4');
        self::assertEquals(2, $map->count());
        self::assertFalse($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
        self::assertEquals('val3', $map->get(['a', 'c']));
    }

    /**
     * @testdox If all keys for multiple required ordered keys exist in the map no new element will be created
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidOverwriteExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');
        $inserted = $map->add(['a', 'c'], 'val4');
        self::assertEquals(2, $map->count());
        self::assertFalse($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
        self::assertEquals('val3', $map->get(['a', 'c']));
    }

    /**
     * @testdox Values can be set/replaced by multiple required keys
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testSetByKeyExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $set = $map->set(['a', 'b'], 'val4');
        self::assertEquals(2, $map->count());
        self::assertTrue($set);
        self::assertEquals('val4', $map->get(['a', 'b']));
        self::assertEquals('val4', $map->get(['b', 'a']));
        self::assertEquals('val3', $map->get(['a', 'c']));
    }

    /**
     * @testdox Values cannot be set/replaced if the multiple required keys don't match or exist
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidSetByKeyExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $set = $map->set('a', 'val4');
        self::assertFalse($set);
        self::assertEquals(2, $map->count());

        $set = $map->set(['b', 'c'], 'val4');
        self::assertFalse($set);
    }

    /**
     * @testdox Values can be set/replaced by multiple required ordered keys if the order is correct
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testSetByKeyExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $set = $map->set(['a', 'b'], 'val4');
        self::assertEquals(2, $map->count());
        self::assertTrue($set);
        self::assertEquals('val4', $map->get(['a', 'b']));
    }

    /**
     * @testdox Values cannot be set/replaced if the multiple required ordered keys don't match or exist in the correct order
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidSetByKeyExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $set = $map->set(['b', 'a'], 'val5');
        self::assertEquals(2, $map->count());
        self::assertFalse($set);
    }

    /**
     * @testdox Multiple keys cannot be remapped
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidRemapExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        $remap = $map->remap(['a', 'b'], ['c', 'd']);

        self::assertFalse($remap);
    }

    /**
     * @testdox All sibling key combinations can be found for multiple required keys
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testSiblingsExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        self::assertEquals([['a', 'b'], ['b', 'a']], $map->getSiblings(['a', 'b']));
    }

    /**
     * @testdox For multiple required ordered keys don't exist any siblings
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testSiblingsExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val2');
        self::assertEquals([], $map->getSiblings(['a', 'b']));
    }

    /**
     * @testdox A multiple required key combination for a value can be removed
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testRemoveExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $removed = $map->remove(['a', 'b']);
        self::assertTrue($removed);
        self::assertCount(1, $map->keys());
        self::assertCount(1, $map->values());
    }

    /**
     * @testdox If a multiple required key combination doesn't exist it cannot be removed
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidRemoveExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $removed = $map->remove('d');
        self::assertFalse($removed);

        $removed = $map->remove(['a', 'b']);
        self::assertFalse($map->removeKey(['a', 'b']));
    }

    /**
     * @testdox A multiple required ordered key combination for a value can be removed
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testRemoveExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $removed = $map->remove(['a', 'b']);
        self::assertTrue($removed);
        self::assertCount(1, $map->keys());
        self::assertCount(1, $map->values());
    }

    /**
     * @testdox If a multiple required ordered key combination doesn't exist it cannot be removed
     * @covers phpOMS\Stdlib\Map\MultiMap
     */
    public function testInvalidRemoveExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $removed = $map->remove(['b', 'a']);
        self::assertFalse($removed);

        $removed = $map->remove(['a', 'b']);
        self::assertFalse($map->removeKey(['a', 'b']));
    }
}
