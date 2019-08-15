<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Math\Parser
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Parser;

/**
 * Basic math function evaluation.
 *
 * @package    phpOMS\Math\Parser
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class Evaluator
{
    /**
     * Evaluate function.
     *
     * @param string $equation Formula to evaluate
     *
     * @return float
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public static function evaluate(string $equation) : ?float
    {
        if (\substr_count($equation, '(') !== \substr_count($equation, ')') || \preg_match('#[^0-9\+\-\*\/\(\)\ \^\.]#', $equation)) {
            return null;
        }

        $stack   = [];
        $postfix = self::shuntingYard($equation);

        foreach ($postfix as $i => $value) {
            if (\is_numeric($value)) {
                $stack[] = $value;
            } else {
                $a = self::parseValue(\array_pop($stack));
                $b = self::parseValue(\array_pop($stack));

                if ($value === '+') {
                    $stack[] = $a + $b;
                } elseif ($value === '-') {
                    $stack[] = $b - $a;
                } elseif ($value === '*') {
                    $stack[] = $a * $b;
                } elseif ($value === '/') {
                    $stack[] = $b / $a;
                } elseif ($value === '^') {
                    $stack[] = $b ** $a;
                }
            }
        }

        $result = \array_pop($stack);

        return \is_numeric($result) ? (float) $result : null;
    }

    /**
     * Parse value.
     *
     * @param mixed $value Value to parse
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function parseValue($value)
    {
        return !\is_string($value) ? $value : (\stripos($value, '.') === false ? (int) $value : (float) $value);
    }

    /**
     * Shunting Yard algorithm.
     *
     * @param string $equation Equation to convert
     *
     * @return array<int, string>
     *
     * @since  1.0.0
     */
    private static function shuntingYard(string $equation) : array
    {
        $stack     = [];
        $operators = [
            '^' => ['precedence' => 4, 'order' => 1],
            '*' => ['precedence' => 3, 'order' => -1],
            '/' => ['precedence' => 3, 'order' => -1],
            '+' => ['precedence' => 2, 'order' => -1],
            '-' => ['precedence' => 2, 'order' => -1],
        ];
        $output    = [];

        $equation = \str_replace(' ', '', $equation);
        $equation = \preg_split('/([\+\-\*\/\^\(\)])/', $equation, -1, \PREG_SPLIT_NO_EMPTY | \PREG_SPLIT_DELIM_CAPTURE);

        if ($equation === false) {
            return [];
        }

        $equation = \array_filter($equation, function($n) {
            return $n !== '';
        });

        foreach ($equation as $i => $token) {
            if (\is_numeric($token)) {
                $output[] = $token;
            } elseif (\strpbrk($token, '^*/+-') !== false) {
                $o1 = $token;
                $o2 = \end($stack);

                while ($o2 !== false && \strpbrk($o2, '^*/+-') !== false
                    && (($operators[$o1]['order'] === -1 && $operators[$o1]['precedence'] <= $operators[$o2]['precedence'])
                        || ($operators[$o1]['order'] === 1 && $operators[$o1]['precedence'] < $operators[$o2]['precedence']))
                ) {
                    $output[] = \array_pop($stack);
                    $o2       = \end($stack);
                }

                $stack[] = $o1;
            } elseif ($token === '(') {
                $stack[] = $token;
            } elseif ($token === ')') {
                while (\end($stack) !== '(') {
                    $output[] = \array_pop($stack);
                }

                \array_pop($stack);
            }
        }

        while (\count($stack) > 0) {
            $output[] = \array_pop($stack);
        }

        /** @var array<int, string> $output */
        return $output;
    }
}
