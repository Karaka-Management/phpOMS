<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Parser;

use phpOMS\Math\Parser\Evaluator;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Parser\Evaluator::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Parser\EvaluatorTest: Evaluator for simple math formulas')]
final class EvaluatorTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Basic formulas using +, -, *, /, () and ^ can be evaluated')]
    public function testBasicEvaluation() : void
    {
        self::assertEqualsWithDelta(4.5, Evaluator::evaluate('3 + 4 * 2 / ( 1 - 5 ) ^ 2 ^ 3 + 1.5'), 2);
        self::assertEqualsWithDelta(4.5, Evaluator::evaluate('3+4*2/(1-5)^2^3+1.5'), 2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Badly formed formulas return null as result')]
    public function testInvalidEvaluation() : void
    {
        self::assertNull(Evaluator::evaluate('invalid'));
        self::assertNull(Evaluator::evaluate('3+4*2/(1-5^2^3+1.5'));
    }
}
