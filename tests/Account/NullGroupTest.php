<?php
/**
 * Karaka
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

namespace phpOMS\tests\Account;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\NullGroup;

/**
 * @testdox phpOMS\tests\Account\NullGroup: Null group
 * @internal
 */
final class NullGroupTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The null group is an instance of the group class
     * @covers phpOMS\Account\NullGroup
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\phpOMS\Account\Group', new NullGroup());
    }

    /**
     * @testdox The null group can get initialized with an id
     * @covers phpOMS\Account\NullGroup
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullGroup(2);
        self::assertEquals(2, $null->getId());
    }

    public function testJsonSerialization() : void
    {
        $null = new NullGroup(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
