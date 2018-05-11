<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Math\Statistic
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic;

/**
 * Basic statistic functions.
 *
 * @package    phpOMS\Math\Statistic
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Basic
{
    /**
     * Constructor.
     *
     * @since  1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {

    }

    /**
     * Calculate frequency.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @param array $values Values
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function frequency(array $values) : array
    {
        $freaquency = [];
        $sum        = 1;

        if (!($isArray = is_array(reset($values)))) {
            $sum = array_sum($values);
        }

        foreach ($values as $value) {
            if (is_array($value)) {
                $freaquency[] = self::frequency($value);
            } else {
                $freaquency[] = $value / $sum;
            }
        }

        return $freaquency;
    }
}
