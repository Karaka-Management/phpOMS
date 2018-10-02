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

namespace phpOMS\tests\Math\Parser;

use phpOMS\Math\Parser\Evaluator;

class EvaluatorTest extends \PHPUnit\Framework\TestCase
{
    public function testBasicEvaluation()
    {
        self::assertEquals(4.5, Evaluator::evaluate('3 + 4 * 2 / ( 1 - 5 ) ^ 2 ^ 3 + 1.5'), '', 2);
        self::assertEquals(4.5, Evaluator::evaluate('3+4*2/(1-5)^2^3+1.5'), '', 2);
        self::assertEquals(null, Evaluator::evaluate('invalid'));
        self::assertEquals(null, Evaluator::evaluate('3+4*2/(1-5^2^3+1.5'));
    }
}
