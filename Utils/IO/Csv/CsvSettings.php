<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Utils\IO\Csv
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Csv;

/**
 * Csv settings.
 *
 * @package phpOMS\Utils\IO\Csv
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class CsvSettings
{
    /**
     * Get csv file delimiter based on file content.
     *
     * @param mixed    $file       File resource
     * @param int      $checkLines Lines to check for evaluation
     * @param string[] $delimiters Potential delimiters
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getFileDelimiter($file, int $checkLines = 2, array $delimiters = [',', "\t", ';', '|', ':']) : string
    {
        $results = [];
        $i       = 0;
        $line    = \fgets($file);

        if ($line === false) {
            return ';'; // @codeCoverageIgnore
        }

        while ($line !== false && $i < $checkLines) {
            ++$i;

            foreach ($delimiters as $delimiter) {
                $regExp = '/[' . $delimiter . ']/';
                $fields = \preg_split($regExp, $line);

                if ($fields === false) {
                    return ';'; // @codeCoverageIgnore
                }

                if (\count($fields) > 1) {
                    if (!empty($results[$delimiter])) {
                        ++$results[$delimiter];
                    } else {
                        $results[$delimiter] = 1;
                    }
                }
            }

            $line = \fgets($file);
        }

        \rewind($file);

        $results = \array_keys($results, \max($results));

        return $results[0];
    }
}
