<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Business\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Business\Finance;

/**
 * Forensics class.
 *
 * @package phpOMS\Business\Finance
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Forensics
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
     * Perform the Benford analysis
     *
     * @param array $data Data to analyze
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function benfordAnalysis(array $data) : array
    {
        $digits = \array_fill(1, 9, 0);
        $size   = \count($data);

        foreach ($data as $number) {
            $digit = \substr((string) $number, 0, 1);
            ++$digits[(int) $digit];
        }

        $results = [];
        foreach ($digits as $digit => $count) {
            $results[$digit] = $count / $size;
        }

        return $results;
    }

    /**
     * Calculate the general Benford distribution
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function expectedBenfordDistribution() : array
    {
        $expected = [];
        for ($i = 1; $i <= 9; ++$i) {
            $expected[$i] = \log10(1 + 1 / $i);
        }

        return $expected;
    }
}
