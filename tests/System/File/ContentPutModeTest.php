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

namespace phpOMS\tests\System\File;

use phpOMS\System\File\ContentPutMode;

/**
 * @internal
 */
final class ContentPutModeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(4, ContentPutMode::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(ContentPutMode::getConstants(), \array_unique(ContentPutMode::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(1, ContentPutMode::APPEND);
        self::assertEquals(2, ContentPutMode::PREPEND);
        self::assertEquals(4, ContentPutMode::REPLACE);
        self::assertEquals(8, ContentPutMode::CREATE);
    }
}
