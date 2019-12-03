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

namespace phpOMS\tests\Config;

use phpOMS\Config\OptionsTrait;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Config\OptionsTrait: Helper for managing otpions
 *
 * @internal
 */
class OptionsTraitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @testdox The option helper has the expected attributes
     * @group framework
     */
    public function testOptionTraitMembers() : void
    {
        $class = new class() {
            use OptionsTrait;
        };

        /* Testing members */
        self::assertObjectHasAttribute('options', $class);
    }

    /**
     * @testdox The option helper has the expected default values after initialization
     * @group framework
     */
    public function testDefault() : void
    {
        $class = new class() {
            use OptionsTrait;
        };

        self::assertFalse($class->exists('someKey'));
        self::assertNull($class->getOption('someKey'));
    }

    /**
     * @testdox Options can be added to the helper
     * @group framework
     */
    public function testAdd() : void
    {
        $class = new class() {
            use OptionsTrait;
        };

        self::assertTrue($class->setOption('a', 'value1'));
        self::assertTrue($class->exists('a'));
        self::assertEquals('value1', $class->getOption('a'));
    }

    /**
     * @testdox Options can be overwritten/changed
     * @group framework
     */
    public function testOverwrite() : void
    {
        $class = new class() {
            use OptionsTrait;
        };

        self::assertTrue($class->setOption('a', 'value1'));
        self::assertTrue($class->exists('a'));
        self::assertEquals('value1', $class->getOption('a'));

        self::assertTrue($class->setOption('a', 'value2'));
        self::assertTrue($class->exists('a'));
        self::assertEquals('value2', $class->getOption('a'));

        self::assertTrue($class->setOption('a', 'value3', true));
        self::assertTrue($class->exists('a'));
        self::assertEquals('value3', $class->getOption('a'));

        self::assertFalse($class->setOption('a', 'value4', false));
        self::assertTrue($class->exists('a'));
        self::assertEquals('value3', $class->getOption('a'));
    }

    /**
     * @testdox Multiple options can be added to the helper in one go
     * @group framework
     */
    public function testAddMultiple() : void
    {
        $class = new class() {
            use OptionsTrait;
        };

        self::assertTrue($class->setOption('a', 'value3', true));
        self::assertTrue($class->exists('a'));
        self::assertEquals('value3', $class->getOption('a'));

        self::assertTrue($class->setOptions(['b' => 2, 'c' => '3'], true));
        self::assertTrue($class->setOptions(['b' => 4, 'c' => '5'], false)); // always returns true
        self::assertTrue($class->exists('a'));
        self::assertTrue($class->exists('b'));
        self::assertTrue($class->exists('c'));

        self::assertEquals('value3', $class->getOption('a'));
        self::assertEquals(2, $class->getOption('b'));
        self::assertEquals(3, $class->getOption('c'));
    }

    /**
     * @testdox Multiple options can be retrieved
     * @group framework
     */
    public function testGetMultiple() : void
    {
        $class = new class() {
            use OptionsTrait;
        };

        self::assertTrue($class->setOption('a', 'value1'));
        self::assertTrue($class->setOption('b', 'value2'));
        self::assertTrue($class->setOption('c', 'value3'));
        self::assertEquals(['a' => 'value1', 'c' => 'value3'], $class->getOptions(['a', 'c']));
    }
}
