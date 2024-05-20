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

use phpOMS\Security\Guard;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Security\Guard::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Security\GuardTest: Basic php source code security inspection')]
final class GuardTest extends \PHPUnit\Framework\TestCase
{
    public function testSafePath() : void
    {
        self::assertTrue(Guard::isSafePath(__DIR__));
        self::assertFalse(Guard::isSafePath('/etc'));
    }

    public function testUnslash() : void
    {
        self::assertEquals(
            [
                'a' => "O'reilly",
                'c' => [
                    'd' => 2,
                    'f' => [
                        'g' => "O'reilly",
                    ],
                ],
            ],
            Guard::unslash(
                [
                    'a' => "O\'reilly",
                    'c' => [
                        'd' => 2,
                        'f' => [
                            'g' => "O\'reilly",
                        ],
                    ],
                ]
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A string can be validated for shell safety')]
    public function testIsShellSafe() : void
    {
        self::assertTrue(Guard::isShellSafe('asdf'));
        self::assertFalse(Guard::isShellSafe('&#;`|*?~<>^()[]{}$\\'));
        self::assertFalse(Guard::isShellSafe('™'));
    }
}
