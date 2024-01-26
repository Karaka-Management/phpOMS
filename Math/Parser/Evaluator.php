<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Parser
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Parser;

/**
 * Basic math function evaluation.
 *
 * @package phpOMS\Math\Parser
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Evaluator
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Evaluate function.
     *
     * @param string $equation Formula to evaluate
     *
     * @return null|float
     *
     * @since 1.0.0
     */
    public static function evaluate(string $equation) : ?float
    {
        if (\substr_count($equation, '(') !== \substr_count($equation, ')')
            || \preg_match('#[^0-9\+\-\*\/\(\)\ \^\.]#', $equation)
        ) {
            return null;
        }

        $stack   = [];
        $postfix = self::shuntingYard($equation);

        foreach ($postfix as $value) {
            if (\is_numeric($value)) {
                $stack[] = $value;
            } else {
                $a = self::parseValue(\array_pop($stack) ?? 0);
                $b = self::parseValue(\array_pop($stack) ?? 0);

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
     * @param int|float|string $value Value to parse
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    private static function parseValue(int | float | string $value) : int | float
    {
        return \is_string($value)
            ? (\stripos($value, '.') === false ? (int) $value : (float) $value)
            : $value;
    }

    /**
     * Shunting Yard algorithm.
     *
     * @param string $equation Equation to convert
     *
     * @return string[]
     *
     * @since 1.0.0
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
        $output = [];

        $equation = \str_replace(' ', '', $equation);
        $equation = \preg_split('/([\+\-\*\/\^\(\)])/', $equation, -1, \PREG_SPLIT_NO_EMPTY | \PREG_SPLIT_DELIM_CAPTURE);

        if ($equation === false) {
            return []; // @codeCoverageIgnore
        }

        $equation = \array_filter($equation, function($n) {
            return $n !== '';
        });

        foreach ($equation as $token) {
            if (\is_numeric($token)) {
                $output[] = $token;
            } elseif (\strpbrk($token, '^*/+-') !== false) {
                $o1 = $token;
                $o2 = \end($stack);

                while ($o2 !== false && \strpbrk($o2, '^*/+-') !== false
                    && (($operators[$o1]['order'] === -1 && $operators[$o1]['precedence'] <= $operators[$o2]['precedence'])
                        /*|| ($operators[$o1]['order'] === 1 && $operators[$o1]['precedence'] < $operators[$o2]['precedence'])*/)
                ) {
                    // The commented part above is always FALSE because this equation always compares 4 < 2|3|4.
                    // Only uncomment if the operators array changes.
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

        while (!empty($stack)) {
            $output[] = \array_pop($stack);
        }

        /** @var string[] $output */
        return $output;
    }
}
