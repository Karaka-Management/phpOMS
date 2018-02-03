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

namespace phpOMS\tests\Business\Finance;

use phpOMS\Business\Finance\Loan;

class LoanTest extends \PHPUnit\Framework\TestCase
{
    public function testRatios()
    {
        self::assertEquals(100 / 50, Loan::getLoanToDepositRatio(100, 50));
        self::assertEquals(100 / 50, Loan::getLoanToValueRatio(100, 50));
    }
}
