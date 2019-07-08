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

namespace phpOMS\tests\Math\Parser;

use phpOMS\Math\Parser\Evaluator;

/**
 * @internal
 */
class EvaluatorTest extends \PHPUnit\Framework\TestCase
{
    public function testBasicEvaluation() : void
    {
        self::assertEqualsWithDelta(4.5, Evaluator::evaluate('3 + 4 * 2 / ( 1 - 5 ) ^ 2 ^ 3 + 1.5'), 2);
        self::assertEqualsWithDelta(4.5, Evaluator::evaluate('3+4*2/(1-5)^2^3+1.5'), 2);
        self::assertNull(Evaluator::evaluate('invalid'));
        self::assertNull(Evaluator::evaluate('3+4*2/(1-5^2^3+1.5'));
    }
}
