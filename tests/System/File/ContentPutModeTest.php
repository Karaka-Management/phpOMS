<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\System\File;

use phpOMS\System\File\ContentPutMode;

class ContentPutModeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(4, \count(ContentPutMode::getConstants()));
        self::assertEquals(ContentPutMode::getConstants(), array_unique(ContentPutMode::getConstants()));

        self::assertEquals(1, ContentPutMode::APPEND);
        self::assertEquals(2, ContentPutMode::PREPEND);
        self::assertEquals(4, ContentPutMode::REPLACE);
        self::assertEquals(8, ContentPutMode::CREATE);
    }
}
