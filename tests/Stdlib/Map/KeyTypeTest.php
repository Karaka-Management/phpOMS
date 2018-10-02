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

namespace phpOMS\tests\Stdlib\Map;

use phpOMS\Stdlib\Map\KeyType;

class KeyTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(2, \count(KeyType::getConstants()));
        self::assertEquals(0, KeyType::SINGLE);
        self::assertEquals(1, KeyType::MULTIPLE);
    }
}
