<?php

class FiniteDifference
{
    const EPSILON = 0.00001;

    public static function getNewtonDifferenceQuotient(\string $formula, array $variables, \int $derivative = 1) : \float
    {
        return (Evaluator::evaluate($formula, ['x' => $variables['x'] + self::EPSILON]) - Evaluator::evaluate($formula, ['x' => $variables['x']])) / self::EPSILON;
    }

    public static function getSymmetricDifferenceQuotient(\string $formula, array $variables, \int $derivative = 1) : \float
    {
        return (Evaluator::evaluate($formula, ['x' => $variables['x'] + self::EPSILON]) - Evaluator::evaluate($formula, ['x' => $variables['x'] - self::EPSILON])) / (2 * self::EPSILON);
    }

    public static function getFivePointStencil(\string $formula, array $variables, \int $derivative = 1) : \float
    {
        if ($derivative === 1) {
            return (-Evaluator::evaluate($formula, ['x' => $variables['x'] + 2 * self::EPSILON]) + 8 * Evaluator::evaluate($formula, ['x' => $variables['x'] + self::EPSILON]) - 8 * Evaluator::evaluate($formula, ['x' => $variables['x'] - self::EPSILON]) + Evaluator::evaluate($formula, ['x' => $variables['x'] - 2 * self::EPSILON])) / (12 * self::EPSILON);
        } elseif ($derivative === 2) {
            return (-Evaluator::evaluate($formula, ['x' => $variables['x'] + 2 * self::EPSILON]) + 16 * Evaluator::evaluate($formula, ['x' => $variables['x'] + self::EPSILON]) - 30 * Evaluator::evaluate($formula, ['x' => $variables['x']]) + 16 * Evaluator::evaluate($formula, ['x' => $variables['x'] - self::EPSILON]) - Evaluator::evaluate($formula, ['x' => $variables['x'] - 2 * self::EPSILON])) / (12 * self::EPSILON ** 2);
        } elseif ($derivative === 3) {
            return (Evaluator::evaluate($formula, ['x' => $variables['x'] + 2 * self::EPSILON]) - 2 * Evaluator::evaluate($formula, ['x' => $variables['x'] + self::EPSILON]) + 2 * Evaluator::evaluate($formula, ['x' => $variables['x'] - self::EPSILON]) - Evaluator::evaluate($formula, ['x' => $variables['x'] - 2 * self::EPSILON])) / (2 * self::EPSILON ** 3);
        } elseif ($derivative === 4) {
            return (Evaluator::evaluate($formula, ['x' => $variables['x'] + 2 * self::EPSILON]) - 4 * Evaluator::evaluate($formula, ['x' => $variables['x'] + self::EPSILON]) + 6 * Evaluator::evaluate($formula, ['x' => $variables['x']]) - 4 * Evaluator::evaluate($formula, ['x' => $variables['x'] - self::EPSILON]) + Evaluator::evaluate($formula, ['x' => $variables['x'] - 2 * self::EPSILON])) / (self::EPSILON ** 4);
        }

        throw new \Exception('Derivative too high');
    }
}
