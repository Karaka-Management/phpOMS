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

namespace phpOMS\tests\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\Query\Grammar\Grammar;

class GrammarTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $grammar = new Grammar();
        self::assertEquals('Y-m-d H:i:s', $grammar->getDateFormat());
        self::assertEquals('', $grammar->getTablePrefix());

        $grammar->setTablePrefix('oms_');
        self::assertEquals('oms_', $grammar->getTablePrefix());
    }
}
