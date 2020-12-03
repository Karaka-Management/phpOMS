<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\System;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\System\CharsetType;

/**
 * @internal
 */
class CharsetTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(3, CharsetType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(CharsetType::getConstants(), \array_unique(CharsetType::getConstants()));
    }

    /**
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
