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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Map;

use phpOMS\Stdlib\Map\KeyType;

/**
 * @internal
 */
class KeyTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(2, KeyType::getConstants());
        self::assertEquals(0, KeyType::SINGLE);
        self::assertEquals(1, KeyType::MULTIPLE);
    }
}
