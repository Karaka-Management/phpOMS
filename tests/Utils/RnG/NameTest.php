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

use phpOMS\Utils\RnG\Name;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\RnG\Name::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\RnG\NameTest: Random name generator')]
final class NameTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Random female and male names can be generated')]
    public function testRandom() : void
    {
        self::assertNotEquals(Name::generateName(['female']), Name::generateName(['male']));
    }
}
