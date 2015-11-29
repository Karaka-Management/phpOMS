<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Validation;

/**
 * Validator class.
 *
 * @category   Framework
 * @package    phpOMS\Validation
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
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function isValid($var, $constraints)
    {
        foreach ($constraints as $callback => $settings) {
            $valid = self::$callback($var, ...$settings);
            $valid = (self::endsWith($callback, 'Not') ? $valid : !$valid);

            if (!$valid) {
                return false;
            }
        }

        return true;
    }

    /**
     * String ends with ?
     *
     * @param \string $haystack String to search in
     * @param \string $needle   String to search for
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function endsWith($haystack, $needle)
    {
        return $needle === '' || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== false;
    }

    /**
     * Validate variable by type.
     *
     * @param mixed     $var        Variable to validate
     * @param \string[] $constraint Array of allowed types
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function isType($var, $constraint)
    {
        foreach ($constraint as $key => $value) {
            if (!is_a($var, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate variable by length.
     *
     * @param \string     $var Variable to validate
     * @param \int|\float $min Min. length
     * @param \int|\float $max Max. length
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function hasLength($var, $min = 0, $max = PHP_INT_MAX)
    {
        $length = strlen($var);

        if ($length <= $max && $length >= $min) {
            return true;
        }

        return false;
    }

    /**
     * Validate variable by substring.
     *
     * @param \string $var    Variable to validate
     * @param \string $substr Substring
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function contains($var, $substr)
    {
        return (strpos($var, $substr) !== false ? true : false);
    }

    /**
     * Validate variable by pattern.
     *
     * @param \string $var     Variable to validate
     * @param \string $pattern Pattern for validation
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function matches($var, $pattern)
    {
        return (preg_match($pattern, $var) !== false ? true : false);
    }

    /**
     * Validate variable by interval.
     *
     * @param \int|\float $var Variable to validate
     * @param \int|\float $min Min. value
     * @param \int|\float $max Max. value
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function hasLimit($var, $min = 0, $max = PHP_INT_MAX)
    {
        if ($var <= $max && $var >= $min) {
            return true;
        }

        return false;
    }

    /**
     * String starts with ?
     *
     * @param \string $haystack String to search in
     * @param \string $needle   String to search for
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function startsWith($haystack, $needle)
    {
        return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }
}
