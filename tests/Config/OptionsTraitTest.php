<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Config;

use phpOMS\Config\OptionsTrait;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Config\OptionsTrait: Helper for managing options')]
final class OptionsTraitTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The option helper has the expected default values after initialization')]
    public function testDefault() : void
    {
        $class = new class() {
            use OptionsTrait;
        };

        self::assertFalse($class->exists('someKey'));
        self::assertNull($class->getOption('someKey'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Options can be added to the helper')]
    public function testAdd() : void
    {
        $class = new class() {
            use OptionsTrait;
        };

        self::assertTrue($class->setOption('a', 'value1'));
        self::assertTrue($class->exists('a'));
        self::assertEquals('value1', $class->getOption('a'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Options can be overwritten/changed')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Multiple options can be added to the helper in one go')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Multiple options can be retrieved')]
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
