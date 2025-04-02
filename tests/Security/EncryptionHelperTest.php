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

namespace phpOMS\tests\Security;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Security\EncryptionHelper;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Security\EncryptionHelperTest: Basic php source code security inspection')]
final class EncryptionHelperTest extends \PHPUnit\Framework\TestCase
{
    public function testSharedKey() : void
    {
        $plain = 'This is a test message.';

        $key       = EncryptionHelper::createSharedKey();
        $encrypted = EncryptionHelper::encryptShared($plain, $key);

        self::assertNotEquals($plain, $encrypted);
        self::assertEquals($plain, EncryptionHelper::decryptShared($encrypted, $key));
    }

    public function testPairedKey() : void
    {
        $plain = 'This is a test message.';

        $keys      = EncryptionHelper::createPairedKey();
        $encrypted = EncryptionHelper::encryptSecret($plain, $keys['alicePrivate'], $keys['bobPublic']);

        self::assertNotEquals($plain, $encrypted);
        self::assertEquals($plain, EncryptionHelper::decryptSecret($encrypted, $keys['bobPrivate'], $keys['alicePublic']));
    }

    public function testFileEncryption() : void
    {
        if (\is_file(__DIR__ . '/encrypted.txt')) {
            \unlink(__DIR__ . '/encrypted.txt');
        }

        if (\is_file(__DIR__ . '/decrypted.txt')) {
            \unlink(__DIR__ . '/decrypted.txt');
        }

        $key = EncryptionHelper::createSharedKey();
        self::assertTrue(EncryptionHelper::encryptFile(__DIR__ . '/plain.txt', __DIR__ . '/encrypted.txt', $key));

        self::assertNotEquals(
            \file_get_contents(__DIR__ . '/plain.txt'),
            \file_get_contents(__DIR__ . '/encrypted.txt')
        );

        self::assertTrue(EncryptionHelper::decryptFile(__DIR__ . '/encrypted.txt', __DIR__ . '/decrypted.txt', $key));

        self::assertEquals(
            \file_get_contents(__DIR__ . '/plain.txt'),
            \file_get_contents(__DIR__ . '/decrypted.txt')
        );

        if (\is_file(__DIR__ . '/encrypted.txt')) {
            \unlink(__DIR__ . '/encrypted.txt');
        }

        if (\is_file(__DIR__ . '/decrypted.txt')) {
            \unlink(__DIR__ . '/decrypted.txt');
        }
    }
}
