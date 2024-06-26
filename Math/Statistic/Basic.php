<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Statistic
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic;

/**
 * Basic statistic functions.
 *
 * @package phpOMS\Math\Statistic
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Basic
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
     * Calculate frequency.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @param array<array|int|float> $values Values
     *
     * @return array<array|int|float>
     *
     * @since 1.0.0
     */
    public static function frequency(array $values) : array
    {
        $frequency = [];
        $sum       = 1;

        if (!(\is_array(\reset($values)))) {
            $sum = \array_sum($values);
        }

        foreach ($values as $value) {
            $frequency[] = \is_array($value) ? self::frequency($value) : $value / $sum;
        }

        return $frequency;
    }
}
