<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\System\File
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System\File;

/**
 * Path exception class.
 *
 * @package phpOMS\System\File
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class SearchUtils
{
    /**
     * Find text in file.
     *
     * All keywords must be found within a certain distance to the first and last find.
     *
     * @param string $path     File path
     * @param array  $keywords Keywords to find
     * @param int    $distance Distance
     *
     * @return array<int, array{start:int, end:int, distance:int}>
     *
     * @since 1.0.0
     */
    public static function findInFile(string $path, array $keywords, int $distance = 500) : array
    {
        $fp = \fopen($path, "r");
        if ($fp === false) {
            return [];
        }

        $positions = [];

        $globalPos = 0;

        while (($line = \fgets($fp)) !== false) {
            foreach ($keywords as $keyword) {
                $pos = \stripos($line, $keyword);

                while ($pos !== false) {
                    $positions[$keyword][] = $globalPos + $pos;

                    $pos = \stripos($line, $keyword, $pos + 1);
                }
            }

            $globalPos += \strlen($line);
        }

        \fclose($fp);

        if (empty($positions) || \count($keywords) !== \count($positions)) {
            return [];
        }

        $start     = \reset($keywords);
        $distances = [];

        foreach ($positions[$start] as $pos) {
            $distance = [
                'start'    => $pos,
                'end'      => $pos,
                'distance' => 0,
            ];

            foreach ($positions as $keyword => $found) {
                $closestStart = null;
                $closestEnd   = null;
                $inBetween    = null;

                foreach ($found as $pos2) {
                    if ($pos2 >= $distance['start'] && $pos2 <= $distance['end']) {
                        $inBetween = $pos2;

                        break;
                    }

                    if ($closestStart === null
                        || \abs($pos2 - $distance['start']) < \abs($closestStart - $distance['start'])
                    ) {
                        $closestStart = $pos2;
                    }

                    if ($closestEnd === null
                        || \abs($pos2 - $distance['end']) < \abs($closestEnd - $distance['end'])
                    ) {
                        $closestEnd = $pos2;
                    }
                }

                // The following is only perfect for inBetween
                // For the other cases there could be a scenario where the closer value is actually bad
                // because for the next keyword the farther one would be inBetween
                if ($inBetween !== null) {
                    continue; // Perfect
                } elseif ($closestStart < $distance['start']
                    && (\abs($closestStart - $distance['start']) <= \abs($closestEnd - $distance['end']) || $closestEnd > $distance['end'])) {
                    $distance['start'] = \min($distance['start'], $closestStart ?? 0);
                } else {
                    $distance['end'] = \max($distance['end'], $closestEnd ?? 0);
                }
            }

            $distance['distance'] = $distance['end'] - $distance['start'];
            $distances[]          = $distance;
        }

        \uasort($distances, function (array $a, array $b) {
            return $a['distance'] <=> $b['distance'];
        });

        return $distances;
    }

    /**
     * Create a text extract from a file from a position and a start and end needle
     *
     * This allows to return for example text extracts from a html file starting with <p> and ending with </p>
     *
     * @param string $path  File path
     * @param int    $pos   Anchor point for the text extract (e.g. found through stripos or findInFile)
     * @param string $start Start needle
     * @param string $end   End needle
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getTextExtract(string $path, int $pos, string $start, string $end) : string
    {
        $fp = \fopen($path, "r");
        if ($fp === false) {
            return '';
        }

        $startPos = -1;
        while (($line = \fgets($fp)) !== false && \ftell($fp) < $pos) {
            if (\stripos($line, $start) !== false) {
                $startPos = \ftell($fp);
            }
        }

        \fseek($fp, $pos);

        $endPos = -1;
        while (($line = \fgets($fp)) !== false) {
            if (\stripos($line, $end) !== false) {
                $endPos = \ftell($fp);
                break;
            }
        }

        if ($startPos === false || $endPos === false
            || $startPos < 0 || $endPos < 0
            || $startPos > $endPos
        ) {
            \fclose($fp);
            return '';
        }

        \fseek($fp, $startPos);
        $extract = \fread($fp, $endPos - $startPos);

        \fclose($fp);

        return $extract === false ? '' : $extract;
    }
}
