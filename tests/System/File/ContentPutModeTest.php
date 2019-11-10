<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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
class ContentPutModeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertCount(4, ContentPutMode::getConstants());
        self::assertEquals(ContentPutMode::getConstants(), \array_unique(ContentPutMode::getConstants()));

        self::assertEquals(1, ContentPutMode::APPEND);
        self::assertEquals(2, ContentPutMode::PREPEND);
        self::assertEquals(4, ContentPutMode::REPLACE);
        self::assertEquals(8, ContentPutMode::CREATE);
    }
}
