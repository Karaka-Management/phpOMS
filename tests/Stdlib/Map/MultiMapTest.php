<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Map;

use phpOMS\Stdlib\Map\KeyType;
use phpOMS\Stdlib\Map\MultiMap;
use phpOMS\Stdlib\Map\OrderType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Map\MultiMap::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Stdlib\Map\MultiMapTest: Map which associates multiple keys with the same value')]
final class MultiMapTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The map has the expected default values and functionality after initialization')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Elements with multiple optional keys can be added')]
    public function testBasicAddAny() : void
    {
        $map = new MultiMap();

        $inserted = $map->add(['a', 'b'], 'val1');
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Elements with multiple optional keys can be returned if any of the keys matches')]
    public function testBasicGetAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val1');
        self::assertEquals('val1', $map->get('a'));
        self::assertEquals('val1', $map->get('b'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Elements can be forcefully overwritten')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('By default elements are not overwritten')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If a element with partially matching keys is already in the map it will be only added for the new key')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If all keys exist in the map no new element will be created')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Values can be set/replaced by key')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Values cannot be set/replaced if the key doesn't exist")]
    public function testInvalidSetByKeyAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $set = $map->set('d', 'val4');
        self::assertFalse($set);
        self::assertEquals(2, $map->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A key cannot be remapped to a none-existing key')]
    public function testInvalidRemapNewAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $remap = $map->remap('b', 'd');
        self::assertEquals(2, $map->count());
        self::assertFalse($remap);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A key can be remapped to the value of an existing key')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If no more keys are associated with a value after a remap the value is removed from the map')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All keys of the map can be returned')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All values of the map can be returned')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Sibling keys can be found')]
    public function testSiblingsAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');

        $siblings = $map->getSiblings('b');
        self::assertEquals(['a'], $siblings);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("If a key doesn't exist or has no siblings no siblings are returned")]
    public function testInvalidSiblingsAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');

        $siblings = $map->getSiblings('d');
        self::assertEmpty($siblings);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A key for a value can be removed')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If the last key of a value is removed the value is also removed from the map')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("If a key doesn't exist it cannot be removed")]
    public function testInvalidRemoveAny() : void
    {
        $map = new MultiMap();

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $removed = $map->remove('d');
        self::assertFalse($removed);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Elements with multiple required keys can be added')]
    public function testBasicAddExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $inserted = $map->add(['a', 'b'], 'val1');
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Elements with multiple required keys can be returned if all match')]
    public function testBasicGetExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val1');
        self::assertEquals('val1', $map->get(['a', 'b']));
        self::assertEquals('val1', $map->get(['b', 'a']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Elements with multiple required keys cannot be returned if they don't match exactly")]
    public function testBasicInvalidGetExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val1');
        self::assertNotEquals('val1', $map->get(['b']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Elements with multiple required and ordered keys can be added')]
    public function testBasicAddExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $inserted = $map->add(['a', 'b'], 'val1');
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Elements with multiple required ordered keys can be if all match in the correct order')]
    public function testBasicGetExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val1');
        self::assertEquals('val1', $map->get(['a', 'b']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Elements with multiple required keys cannot be returned if they don't match exactly in the correct order")]
    public function testBasicInvalidOrderedGetExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val1');
        self::assertNull($map->get(['b', 'a']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Elements with multiple required keys can be forcefully overwritten')]
    public function testOverwriteExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val1');
        $inserted = $map->add(['a', 'b'], 'val2', true);
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Elements with multiple required ordered keys can be forcefully overwritten')]
    public function testOverwriteExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val1');
        $inserted = $map->add(['a', 'b'], 'val2', true);
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An element cannot be added to for multiple required keys if the keys already exist in a different order')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If a element with partially matching multiple keys is already in the map it will be only added for the new key')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Adding differently ordered keys for multiple required keys will create a new entry in the map')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If all keys for multiple required keys exist in the map no new element will be created')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If all keys for multiple required ordered keys exist in the map no new element will be created')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Values can be set/replaced by multiple required keys')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Values cannot be set/replaced if the multiple required keys don't match or exist")]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Values can be set/replaced by multiple required ordered keys if the order is correct')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Values cannot be set/replaced if the multiple required ordered keys don't match or exist in the correct order")]
    public function testInvalidSetByKeyExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $set = $map->set(['b', 'a'], 'val5');
        self::assertEquals(2, $map->count());
        self::assertFalse($set);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Multiple keys cannot be remapped')]
    public function testInvalidRemapExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        $remap = $map->remap('a', 'b');

        self::assertFalse($remap);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All sibling key combinations can be found for multiple required keys')]
    public function testSiblingsExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        self::assertEquals([['a', 'b'], ['b', 'a']], $map->getSiblings(['a', 'b']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("For multiple required ordered keys don't exist any siblings")]
    public function testSiblingsExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val2');
        self::assertEquals([], $map->getSiblings(['a', 'b']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A multiple required key combination for a value can be removed')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("If a multiple required key combination doesn't exist it cannot be removed")]
    public function testInvalidRemoveExact() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $removed = $map->remove('d');
        self::assertFalse($removed);

        $removed = $map->remove(['a', 'b']);
        self::assertFalse($map->removeKey('a'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A multiple required ordered key combination for a value can be removed')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("If a multiple required ordered key combination doesn't exist it cannot be removed")]
    public function testInvalidRemoveExactOrdered() : void
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $map->add(['a', 'b'], 'val2');
        $map->add(['a', 'c'], 'val3');

        $removed = $map->remove(['b', 'a']);
        self::assertFalse($removed);

        $removed = $map->remove(['a', 'b']);
        self::assertFalse($map->removeKey('a'));
    }
}
