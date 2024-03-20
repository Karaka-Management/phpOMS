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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\Phone;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\RnG\Phone::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\RnG\PhoneTest: Random phone number generator')]
final class PhoneTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Random phone numbers can be generated')]
    public function testRnG() : void
    {
        self::assertMatchesRegularExpression('/^\+\d{1,2} \(\d{3,4}\) \d{3,5}\-\d{3,8}$/', Phone::generatePhone());
    }
}
