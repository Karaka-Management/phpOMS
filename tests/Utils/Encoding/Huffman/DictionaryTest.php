<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\Encoding\Huffman;

use phpOMS\Utils\Encoding\Huffman\Dictionary;

/**
 * @internal
 */
class DictionaryTest extends \PHPUnit\Framework\TestCase
{
    public function testInvalidGetCharacter() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->get('as');
    }

    public function testNotExistingGetCharacter() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->get('a');
    }

    public function testInvalidSetCharacter() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->set('as', 'test');
    }

    public function testInvalidSetDuplicateCharacter() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->set('a', '1');
        $dict->set('a', '1');
    }

    public function testInvalidFormattedValue() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->set('a', '1a');
    }
}
