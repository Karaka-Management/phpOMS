<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Validation
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Validation;

use phpOMS\Utils\StringUtils;

/**
 * Validator class.
 *
 * @package    phpOMS\Validation
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
final class Validator extends ValidatorAbstract
{

    /**
     * Validate variable based on multiple factors.
     *
     * @param mixed $var         Variable to validate
     * @param array $constraints Constraints for validation
     *
     * @return bool
     *
     * @throws \BadFunctionCallException this exception is thrown if the callback is not callable
     *
     * @since  1.0.0
     */
    public static function isValid($var, array $constraints = null) : bool
    {
        if ($constraints === null) {
            return true;
        }

        foreach ($constraints as $test => $settings) {
            $callback = StringUtils::endsWith($test, 'Not') ? \substr($test, 0, -3) : (string) $test;

            if (!\is_callable($callback)) {
                throw new \BadFunctionCallException();
            }

            $valid = !empty($settings) ? $callback($var, ...$settings) : $callback($var);
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
     * @param mixed           $var        Variable to validate
     * @param string|string[] $constraint Array of allowed types
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function isType($var, $constraint) : bool
    {
        if (!\is_array($constraint)) {
            $constraint = [$constraint];
        }

        foreach ($constraint as $key => $value) {
            if (!\is_a($var, $value)) {
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
     * @since  1.0.0
     */
    public static function hasLength(string $var, int $min = 0, int $max = \PHP_INT_MAX) : bool
    {
        $length = \strlen($var);

        if ($length <= $max && $length >= $min) {
            return true;
        }

        return false;
    }

    /**
     * Validate variable by substring.
     *
     * @param string       $var    Variable to validate
     * @param array|string $substr Substring
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function contains(string $var, $substr) : bool
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
     * @since  1.0.0
     */
    public static function matches(string $var, string $pattern) : bool
    {
        return (\preg_match($pattern, $var) === 1 ? true : false);
    }

    /**
     * Validate variable by interval.
     *
     * @param float|int $var Variable to validate
     * @param float|int $min Min. value
     * @param float|int $max Max. value
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function hasLimit($var, $min = 0, $max = \PHP_INT_MAX) : bool
    {
        if ($var <= $max && $var >= $min) {
            return true;
        }

        return false;
    }
}
