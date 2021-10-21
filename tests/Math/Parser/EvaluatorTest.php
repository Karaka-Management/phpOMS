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

namespace phpOMS\tests\Math\Parser;

use phpOMS\Math\Parser\Evaluator;

/**
 * @testdox phpOMS\tests\Math\Parser\EvaluatorTest: Evaluator for simple math formulas
 *
 * @internal
 */
final class EvaluatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Basic formulas using +, -, *, /, () and ^ can be evaluated
     * @covers phpOMS\Math\Parser\Evaluator
     * @group framework
     */
    public function testBasicEvaluation() : void
    {
        self::assertEqualsWithDelta(4.5, Evaluator::evaluate('3 + 4 * 2 / ( 1 - 5 ) ^ 2 ^ 3 + 1.5'), 2);
        self::assertEqualsWithDelta(4.5, Evaluator::evaluate('3+4*2/(1-5)^2^3+1.5'), 2);
    }

    /**
     * @testdox Badly formed formulas return null as result
     * @covers phpOMS\Math\Parser\Evaluator
     * @group framework
     */
    public function testInvalidEvaluation() : void
    {
        self::assertNull(Evaluator::evaluate('invalid'));
        self::assertNull(Evaluator::evaluate('3+4*2/(1-5^2^3+1.5'));
    }
}
