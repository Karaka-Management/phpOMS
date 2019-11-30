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

namespace phpOMS\tests\Utils\Encoding\Huffman;

use phpOMS\Utils\Encoding\Huffman\Dictionary;

/**
 * @testdox phpOMS\tests\Utils\Encoding\Huffman\DictionaryTest: Dictionary for the huffman encoding
 *
 * @internal
 */
class DictionaryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Only single characters can be returned from the dictionary. Multiple characters throw a InvalidArgumentException
     * @covers phpOMS\Utils\Encoding\Huffman\Dictionary
     */
    public function testInvalidGetCharacter() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->get('as');
    }

    /**
     * @testdox A none-existing character throws a InvalidArgumentException
     * @covers phpOMS\Utils\Encoding\Huffman\Dictionary
     */
    public function testNotExistingGetCharacter() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->get('a');
    }

    /**
     * @testdox Only single chracters can be set in the dictionary. Multiple characters throw a InvalidArgumentException
     * @covers phpOMS\Utils\Encoding\Huffman\Dictionary
     */
    public function testInvalidSetCharacter() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->set('as', 'test');
    }

    /**
     * @testdox Dictionary elements cannot be overwritten and throw a InvalidArgumentException
     * @covers phpOMS\Utils\Encoding\Huffman\Dictionary
     */
    public function testInvalidSetDuplicateCharacter() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->set('a', '1');
        $dict->set('a', '1');
    }

    /**
     * @testdox Invalid dictionary values throw a InvalidArgumentException
     * @covers phpOMS\Utils\Encoding\Huffman\Dictionary
     */
    public function testInvalidFormattedValue() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->set('a', '1a');
    }
}
