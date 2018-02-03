<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Schema\QueryType;

class QueryTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(4, count(QueryType::getConstants()));
        self::assertEquals(QueryType::getConstants(), array_unique(QueryType::getConstants()));

        self::assertEquals(0, QueryType::SELECT);
        self::assertEquals(1, QueryType::CREATE);
        self::assertEquals(2, QueryType::DROP);
        self::assertEquals(3, QueryType::ALTER);
    }
}
