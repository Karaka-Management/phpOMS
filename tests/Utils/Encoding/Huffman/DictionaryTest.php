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

namespace phpOMS\tests\Utils\Encoding\Huffman;

use phpOMS\Utils\Encoding\Huffman\Dictionary;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Encoding\Huffman\Dictionary::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Encoding\Huffman\DictionaryTest: Dictionary for the huffman encoding')]
final class DictionaryTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Only single characters can be returned from the dictionary. Multiple characters throw a InvalidArgumentException')]
    public function testInvalidGetCharacter() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->get('as');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing character throws a InvalidArgumentException')]
    public function testNotExistingGetCharacter() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->get('a');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Only single characters can be set in the dictionary. Multiple characters throw a InvalidArgumentException')]
    public function testInvalidSetCharacter() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->set('as', 'test');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Dictionary elements cannot be overwritten and throw a InvalidArgumentException')]
    public function testInvalidSetDuplicateCharacter() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->set('a', '1');
        $dict->set('a', '1');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid dictionary values throw a InvalidArgumentException')]
    public function testInvalidFormattedValue() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $dict = new Dictionary();
        $dict->set('a', '1a');
    }
}
