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

namespace phpOMS\tests\Utils\Encoding\Huffman;

use phpOMS\Utils\Encoding\Huffman\Dictionary;

class DictionaryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidGetCharacter() : void
    {
        $dict = new Dictionary();
        $dict->get('as');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotExistingGetCharacter() : void
    {
        $dict = new Dictionary();
        $dict->get('a');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSetCharacter() : void
    {
        $dict = new Dictionary();
        $dict->set('as', 'test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSetDuplicateCharacter() : void
    {
        $dict = new Dictionary();
        $dict->set('a', '1');
        $dict->set('a', '1');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidFormattedValue() : void
    {
        $dict = new Dictionary();
        $dict->set('a', '1a');
    }
}
