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

namespace phpOMS\tests\System;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\System\CharsetType;

/**
 * @testdox phpOMS\tests\System\CharsetTypeTest: Character set type enum
 * @internal
 */
final class CharsetTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The character set type enum has the correct amount of values
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(3, CharsetType::getConstants());
    }

    /**
     * @testdox The character set type enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(CharsetType::getConstants(), \array_unique(CharsetType::getConstants()));
    }

    /**
     * @testdox The character set type enum has the correct values
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals('us-ascii', CharsetType::ASCII);
        self::assertEquals('iso-8859-1', CharsetType::ISO_8859_1);
        self::assertEquals('utf-8', CharsetType::UTF_8);
    }
}
