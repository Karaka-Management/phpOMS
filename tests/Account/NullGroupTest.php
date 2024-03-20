<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Account\NullGroup::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Account\NullGroup: Null group')]
final class NullGroupTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The null group is an instance of the group class')]
    public function testNull() : void
    {
        self::assertInstanceOf('\phpOMS\Account\Group', new NullGroup());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The null group can get initialized with an id')]
    public function testId() : void
    {
        $null = new NullGroup(2);
        self::assertEquals(2, $null->id);
    }

    public function testJsonSerialization() : void
    {
        $null = new NullGroup(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
