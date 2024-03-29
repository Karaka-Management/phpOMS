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
 * @testdox phpOMS\tests\Utils\RnG\NameTest: Random name generator
 *
 * @internal
 */
final class NameTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Random female and male names can be generated
     * @covers phpOMS\Utils\RnG\Name
     * @group framework
     */
    public function testRandom() : void
    {
        self::assertNotEquals(Name::generateName(['female']), Name::generateName(['male']));
    }
}
