<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Business\Programming
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Business\Programming;

/**
 * Programming metrics
 *
 * This class provides basic programming metric calculations.
 *
 * @package phpOMS\Business\Programming
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Metrics
{
    /**
     * Constructor
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Calculate ABC metric score
     *
     * @latex  r = \sqrt{a^{2} + b^{2} + c^{2}}
     *
     * @param int $a Assignments
     * @param int $b Branches
     * @param int $c Conditionals
     *
     * @return int ABC metric score
     *
     * @since 1.0.0
     */
    public static function abcScore(int $a, int $b, int $c) : int
    {
        return (int) \sqrt($a * $a + $b * $b + $c * $c);
    }

    /**
     * Calculate the C.R.A.P score
     *
     * @latex  r = com^{2} \times (1 - cov)^{3} + com
     *
     * @param int   $complexity Complexity
     * @param float $coverage   Coverage
     *
     * @return int CRAP score
     *
     * @since  1.0.0
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public static function CRAP(int $complexity, float $coverage) : int
    {
        return (int) ($complexity ** 2 * (1 - $coverage) ** 3 + $complexity);
    }
}
