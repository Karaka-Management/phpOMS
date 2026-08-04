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

namespace phpOMS\tests\Account;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\NullAccount;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Account\NullAccount::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Account\NullAccount: Null account')]
final class NullAccountTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The null account is an instance of the account class')]
    public function testNull() : void
    {
        self::assertInstanceOf('\phpOMS\Account\Account', new NullAccount());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The null account can get initialized with an id')]
    public function testId() : void
    {
        $null = new NullAccount(2);
        self::assertEquals(2, $null->id);
    }

    public function testJsonSerialization() : void
    {
        $null = new NullAccount(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
