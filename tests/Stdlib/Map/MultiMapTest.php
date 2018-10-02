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

namespace phpOMS\tests\Stdlib\Map;

use phpOMS\Stdlib\Map\MultiMap;
use phpOMS\Stdlib\Map\KeyType;
use phpOMS\Stdlib\Map\OrderType;

class MultiMapTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes()
    {
        $map = new MultiMap();
        self::assertInstanceOf('\phpOMS\Stdlib\Map\MultiMap', $map);

        /* Testing members */
        self::assertObjectHasAttribute('values', $map);
        self::assertObjectHasAttribute('keys', $map);
    }

    public function testDefault()
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

    public function testBasicAddAny()
    {
        $map = new MultiMap();

        $inserted = $map->add(['a', 'b'], 'val1');
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val1', $map->get('a'));
        self::assertEquals('val1', $map->get('b'));
    }

    public function testOverwriteAny()
    {
        $map = new MultiMap();

        $inserted = $map->add(['a', 'b'], 'val1');
        $inserted = $map->add(['a', 'b'], 'val2');
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get('a'));
        self::assertEquals('val2', $map->get('b'));
    }

    public function testOverwritePartialFalseAny()
    {
        $map = new MultiMap();

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);
        self::assertEquals(2, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get('a'));
        self::assertEquals('val2', $map->get('b'));
        self::assertEquals('val3', $map->get('c'));
    }

    public function testOverwriteFalseFalseAny()
    {
        $map = new MultiMap();

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);
        $inserted = $map->add(['a', 'c'], 'val4', false);
        self::assertEquals(2, $map->count());
        self::assertFalse($inserted);
        self::assertEquals('val2', $map->get('a'));
        self::assertEquals('val2', $map->get('b'));
        self::assertEquals('val3', $map->get('c'));
    }

    public function testSetAny()
    {
        $map = new MultiMap();

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);

        $set = $map->set('d', 'val4');
        self::assertFalse($set);
        self::assertEquals(2, $map->count());

        $set = $map->set('b', 'val4');
        self::assertEquals(2, $map->count());
        self::assertTrue($set);
        self::assertEquals('val4', $map->get('b'));
        self::assertEquals('val4', $map->get('a'));
        self::assertEquals('val3', $map->get('c'));
    }

    public function testRemapAny()
    {
        $map = new MultiMap();

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);

        $set = $map->set('d', 'val4');
        $set = $map->set('b', 'val4');

        $remap = $map->remap('b', 'd');
        self::assertEquals(2, $map->count());
        self::assertFalse($remap);

        $remap = $map->remap('d', 'b');
        self::assertEquals(2, $map->count());
        self::assertFalse($remap);

        $remap = $map->remap('d', 'e');
        self::assertEquals(2, $map->count());
        self::assertFalse($remap);

        $remap = $map->remap('b', 'c');
        self::assertEquals(2, $map->count());
        self::assertTrue($remap);
        self::assertEquals('val3', $map->get('b'));
        self::assertEquals('val4', $map->get('a'));
        self::assertEquals('val3', $map->get('c'));
    }

    public function testMapInfoAny()
    {
        $map = new MultiMap();

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);

        $set = $map->set('d', 'val4');
        $set = $map->set('b', 'val4');

        self::assertEquals(3, \count($map->keys()));
        self::assertEquals(2, \count($map->values()));

        self::assertTrue(\is_array($map->keys()));
        self::assertTrue(\is_array($map->values()));
    }

    public function testSiblingsAny()
    {
        $map = new MultiMap();

        $inserted = $map->add(['a', 'b'], 'val2');

        $set = $map->set('d', 'val4');
        $set = $map->set('b', 'val4');

        $siblings = $map->getSiblings('d');
        self::assertEmpty($siblings);
        self::assertEquals(0, \count($siblings));

        $siblings = $map->getSiblings('b');
        self::assertEquals(1, \count($siblings));
        self::assertEquals(['a'], $siblings);
    }

    public function testRemoveAny()
    {
        $map = new MultiMap();

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);

        $set = $map->set('d', 'val4');
        $set = $map->set('b', 'val4');

        $removed = $map->remove('d');
        self::assertFalse($removed);

        $removed = $map->remove('c');
        self::assertTrue($removed);
        self::assertEquals(2, \count($map->keys()));
        self::assertEquals(1, \count($map->values()));

        $removed = $map->removeKey('d');
        self::assertFalse($removed);

        $removed = $map->removeKey('a');
        self::assertTrue($removed);
        self::assertEquals(1, \count($map->keys()));
        self::assertEquals(1, \count($map->values()));
    }

    public function testBasicAddExact()
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $inserted = $map->add(['a', 'b'], 'val1');
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val1', $map->get(['a', 'b']));
        self::assertEquals('val1', $map->get(['b', 'a']));
    }

    public function testBasicAddExactOrdered()
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $inserted = $map->add(['a', 'b'], 'val1');
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val1', $map->get(['a', 'b']));
        self::assertEquals(null, $map->get(['b', 'a']));
    }

    public function testOverwriteExact()
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $inserted = $map->add(['a', 'b'], 'val1');
        $inserted = $map->add(['a', 'b'], 'val2');
        self::assertEquals(1, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
    }

    public function testOverwritePartialFalseExact()
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);
        self::assertEquals(2, $map->count());
        self::assertTrue($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
        self::assertEquals('val3', $map->get(['c', 'a']));
    }

    public function testOverwriteFalseFalseExact()
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);
        $inserted = $map->add(['a', 'c'], 'val4', false);
        self::assertEquals(2, $map->count());
        self::assertFalse($inserted);
        self::assertEquals('val2', $map->get(['a', 'b']));
        self::assertEquals('val3', $map->get(['a', 'c']));
    }

    public function testSetExact()
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);

        $set = $map->set('d', 'val4');
        self::assertFalse($set);
        self::assertEquals(2, $map->count());

        $set = $map->set(['a', 'b'], 'val4');
        self::assertEquals(2, $map->count());
        self::assertTrue($set);
        self::assertEquals('val4', $map->get(['a', 'b']));
        self::assertEquals('val4', $map->get(['b', 'a']));
    }

    public function testSetExactOrdered()
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);

        $set = $map->set('c', 'val4');
        self::assertFalse($set);
        self::assertEquals(2, $map->count());

        $set = $map->set(['a', 'b'], 'val4');
        self::assertEquals(2, $map->count());
        self::assertTrue($set);
        self::assertEquals('val4', $map->get(['a', 'b']));

        $set = $map->set(['b', 'a'], 'val5');
        self::assertEquals(2, $map->count());
        self::assertFalse($set);
    }

    public function testRemapExact()
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $inserted = $map->add(['a', 'b'], 'val2');
        $remap    = $map->remap(['a', 'b'], ['c', 'd']);

        self::assertFalse($remap);
    }

    public function testSiblingsExact()
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $inserted = $map->add(['a', 'b'], 'val2');
        self::assertEquals([['a', 'b'], ['b', 'a']], $map->getSiblings(['a', 'b']));
    }

    public function testSiblingsExactOrdered()
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $inserted = $map->add(['a', 'b'], 'val2');
        self::assertEquals([], $map->getSiblings(['a', 'b']));
    }

    public function testRemoveExact()
    {
        $map = new MultiMap(KeyType::MULTIPLE);

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);

        self::assertEquals(2, \count($map->keys()));
        self::assertEquals(2, \count($map->values()));

        $removed = $map->remove('d');
        self::assertFalse($removed);

        $removed = $map->remove(['a', 'b']);
        self::assertTrue($removed);
        self::assertEquals(1, \count($map->keys()));
        self::assertEquals(1, \count($map->values()));

        self::assertFalse($map->removeKey(['a', 'b']));
    }

    public function testRemoveExactOrdered()
    {
        $map = new MultiMap(KeyType::MULTIPLE, OrderType::STRICT);

        $inserted = $map->add(['a', 'b'], 'val2');
        $inserted = $map->add(['a', 'c'], 'val3', false);

        self::assertEquals(2, \count($map->keys()));
        self::assertEquals(2, \count($map->values()));

        $removed = $map->remove(['b', 'a']);
        self::assertFalse($removed);

        $removed = $map->remove(['a', 'b']);
        self::assertTrue($removed);
        self::assertEquals(1, \count($map->keys()));
        self::assertEquals(1, \count($map->values()));

        self::assertFalse($map->removeKey(['a', 'b']));
    }
}
