<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Validation
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Validation;

use phpOMS\Utils\StringUtils;

/**
 * Validator class.
 *
 * @package phpOMS\Validation
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Validator extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public static function isValid(mixed $var, ?array $constraints = null) : bool
    {
        if ($constraints === null) {
            return true;
        }

        foreach ($constraints as $test => $settings) {
            $callback = StringUtils::endsWith($test, 'Not') ? \substr($test, 0, -3) : (string) $test;

            if (!\is_callable($callback)) {
                throw new \BadFunctionCallException();
            }

            $valid = empty($settings) ? $callback($var) : $callback($var, ...$settings);
            $valid = (StringUtils::endsWith($test, 'Not') ? !$valid : $valid);

            if (!$valid) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate variable by type.
     *
     * @param object|string   $var        Variable to validate
     * @param string|string[] $constraint Array of allowed types
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isType(object | string $var, string | array $constraint) : bool
    {
        if (!\is_array($constraint)) {
            $constraint = [$constraint];
        }

        foreach ($constraint as $value) {
            if (!\is_a($var, $value, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate variable by length.
     *
     * @param string $var Variable to validate
     * @param int    $min Min. length
     * @param int    $max Max. length
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function hasLength(string $var, int $min = 0, int $max = \PHP_INT_MAX) : bool
    {
        $length = \strlen($var);

        return $length <= $max && $length >= $min;
    }

    /**
     * Validate variable by substring.
     *
     * @param string          $var    Variable to validate
     * @param string|string[] $substr Substring
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function contains(string $var, string | array $substr) : bool
    {
        return \is_string($substr) ? \strpos($var, $substr) !== false : StringUtils::contains($var, $substr);
    }

    /**
     * Validate variable by pattern.
     *
     * @param string $var     Variable to validate
     * @param string $pattern Pattern for validation
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function matches(string $var, string $pattern) : bool
    {
        return \preg_match($pattern, $var) === 1;
    }

    /**
     * Validate variable by interval.
     *
     * @param int|float $var Variable to validate
     * @param int|float $min Min. value
     * @param int|float $max Max. value
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function hasLimit(int | float $var, int | float $min = 0, int | float $max = \PHP_INT_MAX) : bool
    {
        return $var <= $max && $var >= $min;
    }
}
